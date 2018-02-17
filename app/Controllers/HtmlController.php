<?php

namespace App\Controllers;

use Cuatromedios\Kusikusi\Http\Controllers\Controller;
use Cuatromedios\Kusikusi\Models\Entity;

class HtmlController extends Controller
{

    public function home (Request $request, Entity $entity) {
        return view('html.home', ['entity' => $entity, 'title' => 'Uy']);
    }
    public function section (Request $request, Entity $entity) {
        return view('html.section', ['entity' => $entity, 'title' => 'Uy']);
    }
    public function page (Request $request, Entity $entity) {
        return view('html.page', ['entity' => $entity, 'title' => 'Uy']);
    }
}
