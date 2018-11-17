<?php

namespace App\Controllers\Web;

use Cuatromedios\Kusikusi\Http\Controllers\Controller;
use Cuatromedios\Kusikusi\Models\EntityModel;
use Illuminate\Http\Request;

class HtmlController extends Controller
{

    public function home (Request $request, EntityModel $entity) {
        $children = EntityModel::getChildren($entity['id']);
        return view('html.home', [
            'entity' => $entity,
            'children' => $children
        ]);
    }
    public function section (Request $request, EntityModel $entity) {
        $children = EntityModel::getChildren($entity['id']);
        return view('html.section', [
            'entity' => $entity,
            'children' => $children
            ]);
    }
    public function page (Request $request, EntityModel $entity) {
        return view('html.page', ['entity' => $entity]);
    }
}
