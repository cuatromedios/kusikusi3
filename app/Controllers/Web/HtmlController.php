<?php

namespace App\Controllers\Web;

use Cuatromedios\Kusikusi\Http\Controllers\Controller;
use Cuatromedios\Kusikusi\Models\Entity;
use Illuminate\Http\Request;
use Leafo\ScssPhp\Compiler;

class HtmlController extends Controller
{

    public function home (Request $request, Entity $entity) {

        $scssFiles = scandir(resource_path('views/styles/'));
        for ($f = 2; $f < count($scssFiles); $f++) {
            $scss = new Compiler();
            $scss->setFormatter('Leafo\ScssPhp\Formatter\Compressed');
            // $scss->addImportPath(resource_path('views/styles/css'));
            $scss->addImportPath(base_path('vendor/zurb/foundation/scss'));
            $css = $scss->compile(file_get_contents(resource_path('views/styles/'.$scssFiles[$f])));
            $cssFiles = str_replace('scss','css',$scssFiles[$f]);
            file_put_contents (base_path('public/styles/'.$cssFiles), $css );
        }

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
