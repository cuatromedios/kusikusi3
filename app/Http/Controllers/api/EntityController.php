<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Entity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class EntityController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Display a listing of all entities.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function find($query = NULL, $fields = NULL,  $lang = NULL, Request $request)
    {
        // TODO: look for a more efficient way to make this, We cannot make a 'with' to get the related data because every row may be a different model. Is there a way to make this Eloquent way?
        $fields = isset($fields) ? $fields : $request->input('fields', NULL);
        $lang = isset($lang) ? $lang : $request->input('lang', Config::get('general.langs')[0]);

        // Join tables based on requested files, both for contents and data models.
        if (isset($fields)) {
            // TODO: Check if the requested fields are valid for the model
            $fieldsForSelect = [];
            $fieldsArray = explode(',', $fields);
            $contentIndex = 0;
            $alreadyJoinedDataTables = [];
            foreach ($fieldsArray as $field) {
                $fieldParts = explode('.', $field);
                switch (count($fieldParts)) {
                    case 1:
                        $fieldsForSelect[] = $field;
                        break;
                    case 2:
                        switch ($fieldParts[0]) {
                            case 'entity':
                            case 'entities':
                            case 'e':
                                // Entity fields doesn't need to be joined, just the fiels to be selected
                                $fieldsForSelect[] = 'entities.'.$fieldParts[1];
                                break;
                            case 'relations':
                            case 'relation':
                            case 'r':
                                // Entity fields doesn't need to be joined, just the fiels to be selected
                                $fieldsForSelect[] = 'ar.tags';
                                break;
                            case 'content':
                            case 'contents':
                            case 'c':
                                // Join contents table for every content field requested
                                $fieldsForSelect[] = 'c'.$contentIndex.'.value as contents.'.$fieldParts[1];
                                $query->leftJoin('contents as c'.$contentIndex, function ($join) use ($contentIndex, $fieldParts, $lang) { $join->on('c'.$contentIndex.'.entity_id', '=', 'entities.id')->where('c'.$contentIndex.'.lang', '=', $lang)->where('c'.$contentIndex.'.field', '=', $fieldParts[1]);});
                                $contentIndex++;
                                break;
                            default:
                                // Join data model
                                //TODO: Restrict to data models and fields
                                $fieldsForSelect[] = $fieldParts[0].'.'.$fieldParts[1].' as data.'.$fieldParts[1];
                                if (!isset($alreadyJoinedDataTables[$fieldParts[0]])) {
                                    $query->leftJoin($fieldParts[0], $fieldParts[0].'.entity_id', '=', 'entities.id');
                                    $alreadyJoinedDataTables[$fieldParts[0]] = TRUE;
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
        }
        // print($query->toSql());
        $collection = $query->get();
        $exploded_collection = [];
        foreach ($collection as $entity) {
            $exploded_entity = [];
            foreach ($entity as $field => $value) {
                if ($field === 'tags') {
                    $exploded_entity['tags'] = explode(',', $value);
                } else if ($value !== null) {
                    array_set($exploded_entity, $field, $value);
                }
            }
            $exploded_collection[] = $exploded_entity;
        }
        return $exploded_collection;
    }

    /**
     * Display the specified entity.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse|string
     */
    public function findOne($id, $fields = NULL, $lang = NULL, Request $request)
    {
        $lang = isset($lang) ? $lang : $request->input('lang', Config::get('general.langs')[0]);
        $entity = Entity::find($id);
        $modelClass = Entity::getDataClass($entity['model']);
        if (count($modelClass::$dataFields) > 0) {
            $entity->data;
        } else {
            $entity->data = [];
        }
        if ($lang === 'raw') {
            $entity->contents;
        } else if ($lang === 'grouped') {
            $contents = [];
            foreach ($entity->contents()->get() as $content) {
                $contents[$content['lang']][$content['field']] = $content['value'];
            }
            $entity['contents'] = $contents;
        } else {
            $contents = [];
            foreach ($entity->contents()->where('lang', $lang)->get() as $content) {
                $contents[$content['field']] = $content['value'];
            }
            $entity['contents'] = $contents;
        }
        if (!$entity instanceof Entity) {
            return $this->sendNotFoundResponse("The entity with id {$id} doesn't exist");
        }

        return $entity;
    }

    /**
     * Display all entities.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse|string
     */
    public function all($fields = NULL, $lang = NULL, Request $request)
    {
        $query = DB::table('entities')->where('deleted_at', NULL);
        $model = isset($model) ? $model : $request->input('model', NULL);
        if (isset($model)) {
            $query->where('model', $model);
        }

        return $this->find($query, NULL, NULL, $request);
    }

    /**
     * Display entity's children.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse|string
     */
    public function relations($id, $fields = NULL, $lang = NULL, Request $request)
    {
        $query =  DB::table('entities')
            ->join('relations as ar', function ($join) use ($id) {
                $join->on('ar.entity_caller_id', '=', 'entities.id')
                    ->where('ar.entity_called_id', '=', $id)
                    ->where('ar.kind', '=', 'ancestor')
                    ->where('ar.position', '=', 1);
            })
            ->where('deleted_at', NULL);
        return $this->find($query, NULL, NULL, $request);
    }

    /**
     * Display entity's children.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse|string
     */
    public function children($id, $fields = NULL, $lang = NULL, $tags = NULL, Request $request)
    {
        $query =  DB::table('entities')
            ->join('relations as ar', function ($join) use ($id) {
                $join->on('ar.entity_caller_id', '=', 'entities.id')
                    ->where('ar.entity_called_id', '=', $id)
                    ->where('ar.kind', '=', 'ancestor')
                    // ->whereRaw('FIND_IN_SET("a",ar.tags)')
                    ->where('ar.position', '=', 1);
            })
            ->where('deleted_at', NULL);
        return $this->find($query, NULL, NULL, $request);
    }

    /**
     * Display entity's parent.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse|string
     */
    public function parent($id, $fields = NULL, $lang = NULL, Request $request)
    {
        $entity = Entity::find($id);
        $parent = $this->findOne($entity->parent, $fields = NULL, $lang, $request);

        return $parent;
    }
}
