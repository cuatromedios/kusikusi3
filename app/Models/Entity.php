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
        'created_at', 'updated_at', 'deleted_at',
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
        if (count($groupedFields['data']) > 0) {
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
    public static function get($query = NULL, $fields = [],  $lang = NULL)
    {
        if (!isset($query)) {
            $query = DB::table('entities');
        }
        $query->where('deleted_at', NULL);
        // TODO: look for a more efficient way to make this, We cannot make a 'with' to get the related data because every row may be a different model. Is there a way to make this Eloquent way?
        // Join tables based on requested files, both for contents and data models.

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
                                // Entity fields doesn't need to be joined, just the fiels to be selected
                                $fieldsForSelect[] = 'entities.'.$groupField;
                                break;
                            case 'relations':
                            case 'relation':
                            case 'r':
                                // Entity fields doesn't need to be joined, just the fiels to be selected
                                $fieldsForSelect[] = 'ar.'.$groupField.' as relation.'.$groupField;
                                break;
                            case 'content':
                            case 'contents':
                            case 'c':
                                // Join contents table for every content field requested
                                if ($groupField === '*') {
                                    $allContentFields = DB::table('contents')->select('field')->groupBy('field')->get();
                                    foreach (array_pluck($allContentFields, 'field') as $contentField) {
                                        $fieldsForSelect[] = 'c'.$contentIndex.'.value as contents.'.$contentField;
                                        $query->leftJoin('contents as c'.$contentIndex, function ($join) use ($contentIndex, $contentField, $lang) { $join->on('c'.$contentIndex.'.entity_id', '=', 'entities.id')->where('c'.$contentIndex.'.lang', '=', $lang)->where('c'.$contentIndex.'.field', '=', $contentField);});
                                        $contentIndex++;
                                    }
                                } else {
                                    $fieldsForSelect[] = 'c'.$contentIndex.'.value as contents.'.$groupField;
                                    $query->leftJoin('contents as c'.$contentIndex, function ($join) use ($contentIndex, $groupField, $lang) { $join->on('c'.$contentIndex.'.entity_id', '=', 'entities.id')->where('c'.$contentIndex.'.lang', '=', $lang)->where('c'.$contentIndex.'.field', '=', $groupField);});
                                }
                                $contentIndex++;
                                break;
                            default:
                                // Join a data model
                                $modelClass =  Entity::getDataClass(str_singular($groupName));
                                if ($groupName === 'data') {
                                    if ($groupField === '*') {
                                        $allDataModels = DB::table('entities')->select('model')->groupBy('model')->get();
                                        foreach (array_pluck($allDataModels, 'model') as $modelName) {
                                            $modelClass =  Entity::getDataClass(str_singular($modelName));
                                            if (count($modelClass::$dataFields) > 0) {
                                                $pluralModelName = str_plural($modelName);
                                                foreach ($modelClass::$dataFields as $dataField) {
                                                    $fieldsForSelect[] = $pluralModelName.'.'.$dataField.' as data.'.$dataField;
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
                                        }
                                    } else {
                                        if (array_search($groupField, $modelClass::$dataFields) !== FALSE) {
                                            $fieldsForSelect[] = $groupName.'.'.$groupField.' as data.'.$groupField;
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
    public static function getChildren($id, $fields = [],  $lang = NULL)
    {
        $query =  DB::table('entities')
            ->join('relations as ar', function ($join) use ($id) {
                $join->on('ar.entity_caller_id', '=', 'entities.id')
                    ->where('ar.entity_called_id', '=', $id)
                    ->where('ar.kind', '=', 'ancestor')
                    // ->whereRaw('FIND_IN_SET("a",ar.tags)')
                    ->where('ar.position', '=', 1);
            });
        return Entity::get($query, $fields, $lang);
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

        self::creating(function($model) {

            // Auto populate the _id field
            if (!isset($model['id'])) {
                $model['id'] = Uuid::uuid4()->toString();
            }

            // Auto populate the model field
            if (!isset($model['model'])) {
                $model['model'] = 'entity';
            }

            $modelClass =  Entity::getDataClass($model['model']);

            // Contents are sent to another table
            if (isset($model['contents'])) {
                $thisEntityContents = $model['contents'];
                $defaultLang = '';
                foreach ($thisEntityContents as $rowOrFieldKey => $rowOrFieldValue) {
                    if (is_integer($rowOrFieldKey)) {
                        // If is an array, then we assume fields come as in the content table
                        Content::create([
                            'entity_id'   =>  $model['id'],
                            'field' =>  $rowOrFieldValue['field'],
                            'lang'  =>  isset($rowOrFieldValue['lang']) ? $rowOrFieldValue['lang'] : $defaultLang ,
                            'value' =>  $rowOrFieldValue['value']
                        ]);
                    } else {
                        // If not, we are going to use the default language and the keys as field names
                        Content::create([
                            'entity_id'   =>  $model['id'],
                            'field' =>  $rowOrFieldKey,
                            'lang'  =>  $defaultLang,
                            'value' =>  $rowOrFieldValue
                        ]);
                    }
                };
                unset($model['contents']);
            };

            // Data are sent to specific table
            if (isset($model['data'])) {
                $dataToInsert = ['entity_id' => $model['id']];
                foreach ($modelClass::$dataFields as $field) {
                    if (isset($model['data'][$field])) {
                        $dataToInsert[$field] = $model['data'][$field];
                    }
                }
                $modelClass::create($dataToInsert);
                unset($model['data']);
            };
        });

        self::created(function($model) {
            // Create the ancestors relations
            if (isset($model['parent']) && $model['parent'] != '') {
                $parentEntity = Entity::find($model['parent']);
                $model->relations()->attach($parentEntity['id'], ['kind' => 'ancestor', 'position' => 1]);
                $ancestors = ($parentEntity->relations()->where('kind', 'ancestor')->orderBy('position'))->get();
                for ($a = 0; $a < count($ancestors); $a++) {
                    $model->relations()->attach($ancestors[$a]['id'], ['kind' => 'ancestor', 'position' => ($a + 2)]);
                }
            };
        });
    }
}
