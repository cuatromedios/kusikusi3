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
        global $lang;
        $lang = isset($lang) ? $lang : $request->input('lang', Config::get('general.langs')[0]);
        if (isset($fields)) {
            // TODO: Check if the requested fields are valid for the model
            $fieldsForSelect = [];
            $fieldsArray = explode(',', $fields);
            global $contentIndex;
            $contentIndex = 0;
            foreach ($fieldsArray as $field) {
                global $fieldParts;
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
                                $fieldsForSelect[] = 'entities.'.$fieldParts[1];
                                break;
                            case 'content':
                            case 'contents':
                            case 'c':
                                $fieldsForSelect[] = 'c'.$contentIndex.'.value as contents.'.$fieldParts[1];
                                $query->leftJoin('contents as c'.$contentIndex, function ($join) { global $contentIndex, $fieldParts, $lang; $join->on('c'.$contentIndex.'.entity_id', '=', 'entities.id')->where('c'.$contentIndex.'.lang', '=', $lang)->where('c'.$contentIndex.'.field', '=', $fieldParts[1]);});
                                $contentIndex++;
                                break;
                            default:
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

            /* ->select(['c1.value as contents.title', 'c1.value as contents.summary', 'entities.id', 'media.format as data.format', 'media.size as data.size'])
            ->leftJoin('media', 'media.entity_id', '=', 'entities.id')
            ->leftJoin('contents as c1', function ($join) {$join->on('c1.entity_id', '=', 'entities.id')->where('c1.lang', '=', 'en')->where('c1.field', '=', 'title');})
            ->leftJoin('contents as c2', function ($join) {$join->on('c2.entity_id', '=', 'entities.id')->where('c2.lang', '=', 'en')->where('c2.field', '=', 'summary');}) */
        $collection = $query->get();
        $exploded_collection = [];
        foreach ($collection as $entity) {
            $exploded_entity = [];
            foreach ($entity as $field => $value) {
                if ($value !== null) {
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
    public function findOne($id, $lang = NULL, Request $request)
    {
        if (!isset($lang)) {
            $lang = $request->input('lang', 'raw');
        }
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
    public function all(Request $request)
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
    public function children($id, Request $request)
    {
        $query =  DB::table('entities')->where('parent', $id)
        ->where('deleted_at', NULL);

        return $this->find($query, NULL, NULL, $request);
    }

    /**
     * Display entity's parent.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse|string
     */
    public function parent($id, $lang = NULL, Request $request)
    {
        $entity = Entity::find($id);
        $parent = $this->findOne($entity->parent, $lang, $request);

        return $parent;
    }
}
