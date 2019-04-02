<?php
namespace App\Controllers\Web;

use App\Models\Entity;
use Cuatromedios\Kusikusi\Http\Controllers\Controller;
use Cuatromedios\Kusikusi\Models\EntityModel;
use Illuminate\Http\Request;

/**
 * Class HtmlController
 *
 * @package App\Controllers\Web
 */
class HtmlController extends Controller
{

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Entity $entity
     *
     * @return \Illuminate\View\View
     */
    public function home(Request $request, Entity $entity)
    {
        $result = $this->common($entity);
        $result['children'] = $this->children($entity);

        return view('html.home', $result);
    }

    /**
     * @param \App\Models\Entity $currentEntity
     *
     * @return array
     */
    private function common(Entity $currentEntity)
    {
        return [
            "entity"    =>
                Entity::where('id', $currentEntity->id)
                      ->withContents()
                      ->firstOrFail()
                      ->compact(),
            "media"     =>
                Entity::mediaOf($currentEntity->id)
                      ->get()->compact(),
            "ancestors" =>
                Entity::ancestorOf($currentEntity->id)
                      ->descendantOf('root')
                      ->withContents('title', 'url')
                      ->get()->compact(),
        ];
    }

    /**
     * @param $entity
     *
     * @return mixed
     */
    private function children($entity)
    {
        $children = Entity::childOf($entity->id)
                          ->withContents('title', 'summary', 'url')
                          ->with(['media' => EntityModel::onlyTags('icon')])
                          ->get()->compact()
        ;

        return $children;
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Entity $entity
     *
     * @return \Illuminate\View\View
     */
    public function section(Request $request, Entity $entity)
    {
        $result = $this->common($entity);
        $result['children'] = $this->children($entity);

        return view('html.section', $result);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Entity $entity
     *
     * @return \Illuminate\View\View
     */
    public function page(Request $request, Entity $entity)
    {
        $result = $this->common($entity);

        return view('html.page', $result);
    }
}
