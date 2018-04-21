<?php

namespace App\Controllers\Web;

use Cuatromedios\Kusikusi\Http\Controllers\Controller;
use Cuatromedios\Kusikusi\Models\Entity;
use Illuminate\Http\Request;

class HtmlController extends Controller
{

    public function home (Request $request, Entity $entity) {
        $children = Entity::getChildren($entity['id']);
        return view('html.home', [
            'entity' => $entity,
            'children' => $children
        ]);
    }
    public function section (Request $request, Entity $entity) {
        $children = Entity::getChildren($entity['id']);
        return view('html.section', [
            'entity' => $entity,
            'children' => $children
            ]);
    }
    public function page (Request $request, Entity $entity) {
        return view('html.page', ['entity' => $entity]);
    }
}
