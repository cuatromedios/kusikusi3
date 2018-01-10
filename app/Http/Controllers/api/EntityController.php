<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Entity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
    public function find($where = NULL, $fields = NULL,  $lang = NULL, Request $request)
    {
        // TODO: look for a more efficient way to make this, We cannot make a 'with' to get the related data because every row may be a different model. Is there a way to make this "Laravel/Eloquent way"?
        $collection = $where
            ->select(['c1.value as contents.en.title', 'entities.*', 'media.format as data.format', 'media.size as data.size'])
            ->leftJoin('media', 'media.entity_id', '=', 'entities.id')
            ->leftJoin('contents as c1', function ($join) {$join->on('c1.entity_id', '=', 'entities.id')->where('c1.lang', '=', 'en')->where('c1.field', '=', 'title');})
            ->get();
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
        /* $models = [];
        foreach ($collection as $entity) {
            $models[$entity->model][] = $entity->id;
        }
        //var_dump($models);
        $data = [];
        foreach ($models as $modelName => $ids) {
            if (Entity::hasDataFields($modelName)) {
                $modelClass = Entity::getDataClass($modelName);
                $data[] = $modelClass::all();
            }
        }
        foreach ($collection as $entity) {
            if (isset($data))
            $entity['data'] = ['O'=>')'];
        } */
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
        $where = DB::table('entities')->where('deleted_at', NULL);
        $model = isset($model) ? $model : $request->input('model', NULL);
        if (isset($model)) {
            $where->where('model', $model);
        }

        return $this->find($where, NULL, NULL, $request);
    }

    /**
     * Display entity's children.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse|string
     */
    public function children($id, Request $request)
    {
        $where = Entity::where('parent', $id)
        ->where('deleted_at', NULL);

        return $this->find($where, NULL, $request);
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
