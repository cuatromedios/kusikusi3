<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;
use App\Models\Content;
use App\Models\Medium;
use Illuminate\Support\Facades\Config;

class Entity extends Model
{

    public static $entityFields = ['id', 'parent', 'model', 'active', 'created_by', 'updated_by', 'publicated_at', 'unpublicated_at'];
    public static $contentFields = [];
    public static $dataFields = [];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'entities';

    /**
     * The primary key
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'created_at', 'updated_at', 'deleted_at', 'entity_version', 'tree_version', 'relations_version', 'full_version'
    ];

    /**
     * Indicates  the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * Get the contents of the Entity.
     */
    public function contents()
    {
        return $this->hasMany('App\Models\Content', 'entity_id');
    }

    /**
     * Get the other models related.
     */
    public function data()
    {
        $modelClass = Entity::getDataClass($this['model']);
        if ($modelClass && count($modelClass::$dataFields) > 0) {
            return $this->hasOne($modelClass);
        } else {
            return $this->hasOne('App\\Models\\Entity', 'id');
        }
    }
    /*
     *  Return a class from a string
     */
    public static function getDataClass($modelName) {
        if ($modelName && $modelName != '') {
            return ("App\\Models\\Data\\".(ucfirst($modelName)));
        } else {
            return NULL;
        }
    }

    /*
     *  Return TRUE if the model has dataField
     */
    public static function hasDataFields($modelName) {
        $modelClass = Entity::getDataClass($modelName);
        return ($modelClass && count($modelClass::$dataFields) > 0);
    }

    /**
     * Returns an entity.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse|string
     */
    public static function getOne($id, $fields = [], $lang = NULL)
    {
        $lang = isset($lang) ? $lang : Config::get('general.langs')[0];
        if (count($fields) === 0) {
            $fields = ['entities.*', 'data.*', 'contents.*'];
        }
        $fieldsArray = is_array($fields) ? $fields : explode(',', $fields);
        $groupedFields = ['entities' => [], 'contents' => [], 'data' => []];
        foreach ($fieldsArray as $field) {
            $fieldParts = explode('.', $field);
            $groupName = trim($fieldParts[0]);
            $groupField = trim($fieldParts[1]);
            switch ($groupName) {
                case 'entity':
                case 'entities':
                case 'e':
                    $groupedFields['entities'][] = $groupField;
                    break;
                case 'contents':
                case 'content':
                case 'c':
                    $groupedFields['contents'][] = $groupField;
                    break;
                default:
                    $groupedFields['data'][] = $groupField;
                    break;
            }
        }

        // Temporary add model and id field if not requested because they are needed, but removed at the final of the function
        if (array_search('model', $groupedFields['entities']) === FALSE && array_search('*', $groupedFields['entities']) === FALSE) {
            $removeModelField = TRUE;
            $groupedFields['entities'][] = 'model';
        } else {
            $removeModelField = FALSE;
        }
        if (array_search('id', $groupedFields['entities']) === FALSE && array_search('*', $groupedFields['entities']) === FALSE) {
            $removeIdField = TRUE;
            $groupedFields['entities'][] = 'id';
        } else {
            $removeIdField = FALSE;
        }

        // ENTITY Fields
        $entity = Entity::where('id',$id)->select($groupedFields['entities'])->firstOrFail();
        $modelClass = Entity::getDataClass($entity['model']);

        // DATA Fields
        if (count($groupedFields['data']) > 0 && count($modelClass::$dataFields) > 0) {
            $entity->data;
            // TODO: This is not the correct way to restrict the fields on the Data model, we are removing them if not needed, but should be better to never call them
            if (array_search('*', $groupedFields['data']) === FALSE) {
                foreach ($entity->data->attributes as $dataFieldName => $dataFieldValue) {
                    if (array_search($dataFieldName, $groupedFields['data']) === FALSE) {
                        unset($entity->data[$dataFieldName]);
                    }
                }
            }
        }

        // CONTENT Fields
        if (count($groupedFields['contents']) > 0) {
            $contentsQuery = $entity->contents();
            if (array_search('*', $groupedFields['contents']) === FALSE) {
                $contentsQuery->whereIn('field', $groupedFields['contents']);
            }
            if ($lang !== 'raw' && $lang !== 'grouped') {
                $contentsQuery->where('lang', $lang);
            }
            $contentsList = $contentsQuery->get();
            $contents = [];
            if ($lang === 'raw') {
                $contents = $contentsList;
            } else if ($lang === 'grouped') {
                foreach ($contentsList as $content) {
                    $contents[$content['lang']][$content['field']] = $content['value'];
                }
                $entity['contents'] = $contentsList;
            } else {
                foreach ($contentsList as $content) {
                    $contents[$content['field']] = $content['value'];
                }
            }
            $entity['contents'] = $contents;
        }


        if ($removeModelField) {
            array_forget($entity, 'model');
        }
        if ($removeIdField) {
            array_forget($entity, 'id');
        }



        return $entity;
    }

    /**
     * Creates an entity.
     *
     * @param $information
     * @return \Illuminate\Http\JsonResponse|string
     */
    public static function post($information)
    {
        //TODO: Sanitize the $information
        $entity = Entity::create($information);
        return $entity['id'];
    }

    /**
     * Updates an entity.
     *
     * @param $information
     * @return \Illuminate\Http\JsonResponse|string
     */
    public static function patch($id, $information)
    {
        //TODO: Sanitize the $information
        $entity = Entity::where("id", $id)->firstOrFail();
        $entity->update($information);
        $entity->save();
        return $entity['id'];
    }

    /**
     * Returns an entity's parent.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse|string
     */
    public static function getParent($id, $fields = [], $lang = NULL)
    {
        $entity = Entity::find($id);
        $parent = Entity::getOne($entity->parent, $fields , $lang);

        return $parent;
    }

    /**
     * Get a list of entities.
     *
     * @param \Illuminate\Database\Query\Builder $query A DB query builder instance or null
     * @param array $query A DB query builder instance or null
     * @param array $fields An array of strings representing a field like entities.model, contents.title or media.format
     * @param string $lang The name of the language for content fields or null. If null, default language will be taken.
     * @return Collection
     */
    public static function get($query = NULL, $fields = [],  $lang = NULL, $order = NULL)
    {
        if (!isset($query)) {
            $query = DB::table('entities');
        }
        $query->where('deleted_at', NULL);

        // Preprocess the order fields
        // TODO: This may be more efficient using REGEX
        // TODO: If the order has more than two sentences, SHOULD respect the order, currentlly this doesn't happens, they are added in the orderd the fields are processed. This is because we need the table name alias.
        $order = isset($order) ? is_array($order) ? $order : explode(',', $order) : [];
        $collapsedOrders = [];
        foreach ($order as $orderItem) {
            $orderItem = str_replace(['.', ':'], '.', $orderItem);
            $orderItemParts = explode('.', $orderItem);
            if (count($orderItemParts) > 1) {
                if (count($orderItemParts) < 3 || (isset($orderItemParts[2]) && $orderItemParts[2] !== 'desc')) {
                    $orderItemParts[2] = 'asc';
                }
                if ($orderItemParts[0] === 'e' || $orderItemParts[0] === 'entity') { $orderItemParts[0] = 'entities'; }
                else if ($orderItemParts[0] === 'c' || $orderItemParts[0] === 'content') { $orderItemParts[0] = 'contents'; }
                else if ($orderItemParts[0] === 'r' || $orderItemParts[0] === 'relation') { $orderItemParts[0] = 'relations'; }
                else { $orderItemParts[0] = 'data'; }
                $collapsedOrders[$orderItemParts[0].'.'.$orderItemParts[1]] = $orderItemParts[2];
            }
        }

        // TODO: look for a more efficient way to make this, We cannot make a 'with' to get the related data because every row may be a different model. Is there a way to make this Eloquent way?
        // Join tables based on requested fields, both for contents and data models.

        if (count($fields) === 0) {
            $fields = ['entities.*', 'data.*', 'contents.*'];
        }

        if (count($fields) > 0) {
            // TODO: Check if the requested fields are valid for the model
            $fieldsForSelect = [];
            $fieldsArray = is_array($fields) ? $fields : explode(',', $fields);
            $contentIndex = 0;
            $alreadyJoinedDataTables = [];
            foreach ($fieldsArray as $field) {
                $fieldParts = explode('.', $field);
                $groupName = trim($fieldParts[0]);
                $groupField = trim($fieldParts[1]);
                switch (count($fieldParts)) {
                    case 1:
                        $fieldsForSelect[] = $field;
                        break;
                    case 2:
                        switch ($groupName) {
                            case 'entity':
                            case 'entities':
                            case 'e':
                                // Entity fields doesn't need to be joined, just the fields to be selected
                                $fieldsForSelect[] = 'entities.'.$groupField;
                                if ( isset($collapsedOrders['entities.'.$groupField]) ) {
                                    $query->orderBy('entities.'.$groupField, $collapsedOrders['entities.'.$groupField]);
                                }
                                break;
                            case 'relations':
                            case 'relation':
                            case 'r':
                                // Entity fields doesn't need to be joined, just the fiels to be selected
                                if ($groupField === '*') {
                                    $relationFields = ['kind', 'position', 'tags', 'depth'];
                                    foreach ($relationFields as $relationField) {
                                        $fieldsForSelect[] = 'ar.'.$relationField.' as relation.'.$relationField;
                                        if ( isset($collapsedOrders['relations.'.$relationField]) ) {
                                            $query->orderBy('ar.'.$relationField, $collapsedOrders['relations.'.$relationField]);
                                        }
                                    }
                                } else {
                                    $fieldsForSelect[] = 'ar.'.$groupField.' as relation.'.$groupField;
                                    if ( isset($collapsedOrders['relations.'.$groupField]) ) {
                                        $query->orderBy('ar.'.$groupField, $collapsedOrders['relations.'.$groupField]);
                                    }
                                }
                                break;
                            case 'content':
                            case 'contents':
                            case 'c':
                                // Join contents table for every content field requested
                                if ($groupField === '*') {
                                    $allContentFields = DB::table('contents')->select('field')->groupBy('field')->get();
                                    foreach (array_pluck($allContentFields, 'field') as $contentField) {
                                        $tableAlias = 'c'.$contentIndex;
                                        $fieldsForSelect[] = $tableAlias.'.value as contents.'.$contentField;
                                        $query->leftJoin('contents as c'.$contentIndex, function ($join) use ($contentIndex, $contentField, $lang, $tableAlias) { $join->on($tableAlias.'.entity_id', '=', 'entities.id')->where($tableAlias.'.lang', '=', $lang)->where($tableAlias.'.field', '=', $contentField);});
                                        if ( isset($collapsedOrders['contents.'.$contentField]) ) {
                                            // print($tableAlias.'.value'." : ".$collapsedOrders['entities.'.$contentField]);
                                            $query->orderBy($tableAlias.'.value', $collapsedOrders['contents.'.$contentField]);
                                        }
                                        $contentIndex++;
                                    }
                                } else {
                                    $tableAlias = 'c'.$contentIndex;
                                    $fieldsForSelect[] = $tableAlias.'.value as contents.'.$groupField;
                                    $query->leftJoin('contents as c'.$contentIndex, function ($join) use ($contentIndex, $groupField, $lang, $tableAlias) { $join->on($tableAlias.'.entity_id', '=', 'entities.id')->where($tableAlias.'.lang', '=', $lang)->where($tableAlias.'.field', '=', $groupField);});
                                    if ( isset($collapsedOrders['contents.'.$groupField]) ) {
                                        $query->orderBy($tableAlias.'.value', $collapsedOrders['contents.'.$groupField]);
                                    }
                                    $contentIndex++;
                                }
                                $contentIndex++;
                                break;
                            default:
                                // Join a data model
                                // TODO: Do not try to join a data model that doesn't exist
                                // TODO: It seems there is a bug where data fields get duplicated in the SQL sentence
                                if ($groupName === 'd') {$groupName = 'data';}
                                $modelClass =  Entity::getDataClass(str_singular($groupName));
                                if ($groupName === 'data') {
                                    $allDataModels = DB::table('entities')->select('model')->groupBy('model')->get();
                                    if ($groupField === '*') {
                                        foreach (array_pluck($allDataModels, 'model') as $modelName) {
                                            $modelClass =  Entity::getDataClass(str_singular($modelName));
                                            if (count($modelClass::$dataFields) > 0) {
                                                $pluralModelName = str_plural($modelName);
                                                foreach ($modelClass::$dataFields as $dataField) {
                                                    $fieldsForSelect[] = $pluralModelName.'.'.$dataField.' as data.'.$dataField;
                                                    if ( isset($collapsedOrders['data.'.$dataField]) ) {
                                                        $query->orderBy($pluralModelName.'.'.$dataField, $collapsedOrders['data.'.$dataField]);
                                                    }
                                                }
                                                if (!isset($alreadyJoinedDataTables[$pluralModelName])) {
                                                    $query->leftJoin($pluralModelName, $pluralModelName.'.entity_id', '=', 'entities.id');
                                                    $alreadyJoinedDataTables[$pluralModelName] = TRUE;
                                                }
                                            }
                                        }
                                    } else {
                                        foreach (array_pluck($allDataModels, 'model') as $modelName) {
                                            $modelClass =  Entity::getDataClass(str_singular($modelName));
                                            if (count($modelClass::$dataFields) > 0) {
                                                $pluralModelName = str_plural($modelName);
                                                foreach ($modelClass::$dataFields as $dataField) {
                                                    if ($dataField === $groupField) {
                                                        $fieldsForSelect[] = $pluralModelName.'.'.$dataField.' as data.'.$dataField;
                                                        if ( isset($collapsedOrders['data.'.$dataField]) ) {
                                                            $query->orderBy($pluralModelName.'.'.$dataField, $collapsedOrders['data.'.$dataField]);
                                                        }
                                                    }
                                                }
                                                if (!isset($alreadyJoinedDataTables[$pluralModelName])) {
                                                    $query->leftJoin($pluralModelName, $pluralModelName.'.entity_id', '=', 'entities.id');
                                                    $alreadyJoinedDataTables[$pluralModelName] = TRUE;
                                                }
                                            }
                                        }
                                    }
                                } else {
                                    if ($groupField === '*') {
                                        foreach ($modelClass::$dataFields as $dataField) {
                                            $fieldsForSelect[] = $groupName.'.'.$dataField.' as data.'.$dataField;
                                            if ( isset($collapsedOrders['data.'.$dataField]) ) {
                                                $query->orderBy($groupName.'.'.$dataField, $collapsedOrders['data.'.$dataField]);
                                            }
                                        }
                                    } else {
                                        if (array_search($groupField, $modelClass::$dataFields) !== FALSE) {
                                            $fieldsForSelect[] = $groupName.'.'.$groupField.' as data.'.$groupField;
                                            if ( isset($collapsedOrders['data.'.$groupField]) ) {
                                                $query->orderBy($groupName.'.'.$groupField, $collapsedOrders['data.'.$groupField]);
                                            }
                                        }
                                    }
                                    if (!isset($alreadyJoinedDataTables[$groupName])) {
                                        $query->leftJoin($groupName, $groupName.'.entity_id', '=', 'entities.id');
                                        $alreadyJoinedDataTables[$groupName] = TRUE;
                                    }
                                }
                                break;
                        }
                        break;
                    default:
                        break;
                }
            }
            if (count($fieldsForSelect) > 0) {
                $query->select($fieldsForSelect);
            }
        } else {
            $query->select('entities.*');
        }
        //var_dump($collapsedOrders);
        //print ($query->toSql());
        $collection = $query->get();
        $exploded_collection = new Collection();
        foreach ($collection as $entity) {
            $exploded_entity = [];
            foreach ($entity as $field => $value) {
                if ($field === 'tags' || $field === 'relation.tags') {
                    $exploded_entity['relation']['tags'] = explode(',', $value);
                } else if ($value !== null) {
                    array_set($exploded_entity, $field, $value);
                }
            }
            $exploded_collection[] = $exploded_entity;
        }
        return $exploded_collection;
    }

    /**
     * Get a list of children.
     *
     * @param string $id The id of the entity whose parent need to be returned
     * @param array $fields An array of strings representing a field like entities.model, contents.title or media.format
     * @param string $lang The name of the language for content fields or null. If null, default language will be taken.
     * @return Collection
     */
    public static function getChildren($id, $fields = [],  $lang = NULL, $order = 'entities.created_at.desc')
    {
        $query =  DB::table('entities')
            ->join('relations as ar', function ($join) use ($id) {
                $join->on('ar.entity_caller_id', '=', 'entities.id')
                    ->where('ar.entity_called_id', '=', $id)
                    ->where('ar.kind', '=', 'ancestor')
                    // ->whereRaw('FIND_IN_SET("a",ar.tags)')
                    ->where('ar.depth', '=', 0);
            });
        return Entity::get($query, $fields, $lang, $order);
    }

    /**
     * Get a list of children.
     *
     * @param string $id The id of the entity whose ancestors need to be returned
     * @param array $fields An array of strings representing a field like entities.model, contents.title or media.format
     * @param string $lang The name of the language for content fields or null. If null, default language will be taken.
     * @return Collection
     */
    public static function getAncestors($id, $fields = [],  $lang = NULL, $order = ['relations.position'])
    {
        $query =  DB::table('entities')
            ->join('relations as ar', function ($join) use ($id) {
                $join->on('ar.entity_called_id', '=', 'entities.id')
                    ->where('ar.entity_caller_id', '=', $id)
                    ->where('ar.kind', '=', 'ancestor');
            });
        return Entity::get($query, $fields, $lang, $order);
    }

    /**
     * Get a list of descendants.
     *
     * @param string $id The id of the entity whose descendants need to be returned
     * @param array $fields An array of strings representing a field like entities.model, contents.title or media.format
     * @param string $lang The name of the language for content fields or null. If null, default language will be taken.
     * @param array $order The list of order sentences like ['contents.title:asc'].
     * @return Collection
     */
    public static function getDescendants($id, $fields = [],  $lang = NULL, $order = ['relations.depth:asc'])
    {
        $query =  DB::table('entities')
            ->join('relations as ar', function ($join) use ($id) {
                $join->on('ar.entity_caller_id', '=', 'entities.id')
                    ->where('ar.entity_called_id', '=', $id)
                    ->where('ar.kind', '=', 'ancestor');
            });
        return Entity::get($query, $fields, $lang, $order);
    }


    /**
     * The roles that belong to the user.
     */
    public function relations()
    {
        return $this->belongsToMany('App\Models\Entity', 'relations', 'entity_caller_id', 'entity_called_id')
            ->using('App\Models\Relation')
            ->as('relations')
            ->withPivot('kind', 'position', 'tags')
            ->withTimestamps();
    }


    /**
     * Events.
     *
     * @var bool
     */
    public static function boot()
    {
        parent::boot();

        self::updating(function($model) {
            // TODO: Dont allow to change the model?

            // Preprocess the model data if the data class has a method defined for that. (Data models does not extend Entity)
            $modelClass =  Entity::getDataClass($model['model']);
            if (method_exists($modelClass, 'beforeSave')) {
                $model = $modelClass::beforeSave($model);
            }

            // Contents are sent to another table
            $model = Entity::replaceContent($model);
            unset($model['contents']);

            // Data are sent to specific table
            $model = Entity::replaceData($model);

            // TODO: Allow recreation of the tree when updating (now just disallow the change of the parent)
            unset($model['parent']);
            // For reference this is the code used in previous versions of kusikusi, please note we may not need
            // to make a queue because we can now get the descendants ordered by depth:
            /*
             * $currentParent = $this->_properties['parent'];
                    $entityQueue = array();
                    array_push($entityQueue, $this);
                    $index = 0;
                    while (sizeof($entityQueue) > 0) {
                        $currentEntity = $entityQueue[$index];
                        //Solo al primer elemento se cambia su parent, para los demas solo se re-crea su arbol
                        if ($index > 0) {
                            $currentParent = $currentEntity->parent;
                        }
                        unset($entityQueue[$index]);
                        $index++;
                        //Se borran las antiguas relaciones con sus ancestros
                        $ancestors = $currentEntity->getAncestors();
                        foreach ($ancestors as $ancestor) {
                            Relation::deleteRelation($currentEntity->id, $ancestor->id, 'ANCESTOR');
                        }
                        //Se agrega la relacion con su nuevo padre
                        Relation::addRelation($currentEntity->id, $currentParent, 'ANCESTOR');

                        //Se agregan los hijos al queue
                        $children = $currentEntity->getChildren();
                        foreach($children as $child) {
                            array_push($entityQueue, $child);
                        }
                    }
             */

        });

        self::creating(function(Entity $model) {

            // Auto populate the _id field
            if (!isset($model['id'])) {
                $model['id'] = Uuid::uuid4()->toString();
            }

            // Auto populate the model field
            if (!isset($model['model'])) {
                throw new \Error('A model name is requiered');
            }

            //TODO: Check if the parent allows this model as a children

            // Preprocess the model data if the data class has a method defined for that. (Data models does not extend Entity)
            $modelClass =  Entity::getDataClass($model['model']);
            if (method_exists($modelClass, 'beforeSave')) {
                $model = $modelClass::beforeSave($model);
            }

            // Contents are sent to another table
            $model = Entity::replaceContent($model);

            // Data are sent to specific table
            $model = Entity::replaceData($model);

        });



        self::saved(function(Entity $model) {
            // Create the ancestors relations
            if (isset($model['parent']) && $model['parent'] != '') {
                $parentEntity = Entity::find($model['parent']);
                $model->relations()->attach($parentEntity['id'], ['kind' => 'ancestor', 'depth' => 0]);
                $ancestors = ($parentEntity->relations()->where('kind', 'ancestor')->orderBy('depth'))->get();
                for ($a = 0; $a < count($ancestors); $a++) {
                    $model->relations()->attach($ancestors[$a]['id'], ['kind' => 'ancestor', 'depth' => ($a + 1)]);
                }
            };

            // Now, update the versions
            // First the own entity version
            DB::statement(sprintf('update entities set entity_version = entity_version + 1 where id = "%s"', $model['id']));
            // Then the three veresion, using its ancestors

        });
    }

    public static function replaceContent($model) {
        if (isset($model['contents'])) {
            $defaultLang = Config::get('general.langs')[0];
            foreach ($model['contents'] as $rowOrFieldKey => $rowOrFieldValue) {
                if (is_integer($rowOrFieldKey)) {
                    // If is an array, then we assume fields come as in the content table
                    $contentRowKeys = [
                        'entity_id'   =>  $model['id'],
                        'field' =>  $rowOrFieldValue['field'],
                        'lang'  =>  isset($rowOrFieldValue['lang']) ? $rowOrFieldValue['lang'] : $defaultLang
                    ];
                    $contentRowValue = ['value' =>  $rowOrFieldValue['value']];
                } else {
                    // If not, we are going to use the default language and the keys as field names
                    $contentRowKeys = [
                        'entity_id'   =>  $model['id'],
                        'field' =>  $rowOrFieldKey,
                        'lang'  =>  $defaultLang,
                    ];
                    $contentRowValue = ['value' =>  $rowOrFieldValue];
                }
                if (!isset($model['name']) && $contentRowKeys['field'] === 'title' && $contentRowKeys['lang'] === $defaultLang) {
                    $model['name'] = $contentRowValue['value'];
                }
            };
        };
        unset($model['contents']);
        return $model;
    }
    public static function replaceData($model) {
        $modelClass =  Entity::getDataClass($model['model']);
        if (isset($model['data'])) {
            $dataToInsert = ['entity_id' => $model['id']];
            foreach ($modelClass::$dataFields as $field) {
                if (isset($model['data'][$field])) {
                    $dataToInsert[$field] = $model['data'][$field];
                }
            }
            $modelClass::updateOrCreate(['entity_id' => $model['id']], $dataToInsert);
        };
        unset($model['data']);
        return $model;
    }
}
