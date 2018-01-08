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
    public function index(Request $request)
    {
        return Entity::all();
    }

    /**
     * Display the specified entity.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse|string
     */
    public function show($id)
    {
        $entity = Entity::find($id);
        $model = $entity['model'];
        $modelClass = ("App\\Models\\Data\\".(ucfirst($entity['model'])));
        if (count($modelClass::$dataFields) > 0) {
            $entity->data;
        } else {
            $entity->data = [];
        }
        $entity->contents;
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
