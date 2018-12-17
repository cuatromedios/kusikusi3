<?php

namespace App\Controllers\Web;

use Cuatromedios\Kusikusi\Http\Controllers\Controller;
use App\Models\Entity;
use Illuminate\Http\Request;

class HtmlController extends Controller
{

  private function common(Entity $currentEntity) {
    return  [
      "entity" =>
              Entity::where('id', $currentEntity->id)
              ->withContents()
              ->firstOrFail()
              ->compact(),
      "media" =>
              Entity::mediaOf($currentEntity->id)
              ->get()->compact(),
      "ancestors" =>
              Entity::ancestorOf($currentEntity->id)
              ->descendantOf('root')
              ->withContents('title', 'url')
              ->get()->compact()
      ];
  }
  private function children($entity) {
    $children = Entity::childOf($entity->id)
            ->withContents('title', 'summary', 'url')
            ->withMedia('icon')
            ->get()->compact();
    return $children;
  }

  public function home(Request $request, Entity $entity)
  {
    $result = $this->common($entity);
    $result['children'] = $this->children($entity);
    return view('html.home', $result);
  }

  public function section(Request $request, Entity $entity)
  {
    $result = $this->common($entity);
    $result['children'] = $this->children($entity);
    return view('html.section', $result);
  }

  public function page(Request $request, Entity $entity)
  {
    $result = $this->common($entity);
    return view('html.page', $result);
  }
}
