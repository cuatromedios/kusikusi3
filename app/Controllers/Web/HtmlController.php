<?php

namespace App\Controllers\Web;

use Cuatromedios\Kusikusi\Http\Controllers\Controller;
use App\Models\Entity;
use Cuatromedios\Kusikusi\Models\EntityModel;
use Illuminate\Http\Request;

class HtmlController extends Controller
{

  private function common(Request $request, Entity $currentEntity) {
    $result = [
      "entity" =>
              Entity::where('id', $currentEntity->id)
              ->withContents(null, $request->lang)
              ->firstOrFail()
              ->compact(),
      "media" =>
              Entity::mediaOf($currentEntity->id)
              ->get()->compact(),
      "ancestors" =>
              Entity::ancestorOf($currentEntity->id)
              ->descendantOf('root')
              ->withContents(['title', 'url'], $request->lang)
              ->get()->compact()
      ];
      return $result;
  }
  private function children(Request $request, Entity $entity) {
    $children = Entity::childOf($entity->id)
            ->withContents(['title', 'summary', 'url'], $request->lang)
            ->with(['media' => EntityModel::onlyTags('icon')])
            ->get()->compact();
    return $children;
  }

  public function home(Request $request, Entity $entity)
  {
    $result = $this->common($request, $entity);
    $result['children'] = $this->children($request, $entity);
    return view('html.home', $result);
  }

  public function section(Request $request, Entity $entity)
  {
    $result = $this->common($request, $entity);
    $result['children'] = $this->children($request, $entity);
    return view('html.section', $result);
  }

  public function page(Request $request, Entity $entity)
  {
    $result = $this->common($request, $entity);
    return view('html.page', $result);
  }
}
