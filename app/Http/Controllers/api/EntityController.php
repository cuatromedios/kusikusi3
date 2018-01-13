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
     * Display the specified entity.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse|string
     */
    public function getOne($id, Request $request)
    {
        $lang = $request->input('lang', Config::get('general.langs')[0]);
        $fields = $request->input('fields', []);
        $entity = Entity::getOne($id, $fields, $lang);

        if (!$entity instanceof Entity) {
            return $this->sendNotFoundResponse("The entity with id {$id} doesn't exist");
        }
        return $entity;
    }

    /**
     * Display entity's parent.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse|string
     */
    public function getParent($id, Request $request)
    {
        $lang = $request->input('lang', Config::get('general.langs')[0]);
        $fields = $request->input('fields', []);
        $entity = Entity::getParent($id, $fields, $lang);

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
    public function get(Request $request)
    {
        $fields = $request->input('fields', []);
        $lang = $request->input('lang', Config::get('general.langs')[0]);

        $collection = Entity::get(NULL, $fields, $lang);

        return $collection;
    }

    /**
     * Display entity's children.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse|string
     */
    public function getChildren($id, Request $request)
    {
        $fields = $request->input('fields', []);
        $lang = $request->input('lang', Config::get('general.langs')[0]);
        $order = $request->input('order', NULL);

        $collection = Entity::getChildren($id, $fields, $lang, $order);

        return $collection;
    }

    /**
     * Display entity's ancestors.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse|string
     */
    public function getAncestors($id, Request $request)
    {
        $fields = $request->input('fields', []);
        $lang = $request->input('lang', Config::get('general.langs')[0]);
        $order = $request->input('order', NULL);

        $collection = Entity::getAncestors($id, $fields, $lang, $order);

        return $collection;
    }

    /**
     * Display entity's ancestors.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse|string
     */
    public function getDescendants($id, Request $request)
    {
        $fields = $request->input('fields', []);
        $lang = $request->input('lang', Config::get('general.langs')[0]);
        $order = $request->input('order', NULL);

        $collection = Entity::getDescendants($id, $fields, $lang, $order);

        return $collection;
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
}
