<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Entity;
use Illuminate\Http\Request;

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
    public function find(Request $request)
    {
        return Entity::all();
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
        $entity->relations;
        if (!$entity instanceof Entity) {
            return $this->sendNotFoundResponse("The entity with id {$id} doesn't exist");
        }

        return $entity;
    }
    /**
     * Display entity's children.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse|string
     */
    public function children($id)
    {
        $children = Entity::where('parent', $id)
            ->get();

        return $children;
    }
}
