<?php
namespace App\Models;

use Cuatromedios\Kusikusi\Models\DataModel;
use Cuatromedios\Kusikusi\Models\EntityContent;
use Illuminate\Support\Facades\Config;

/**
 * Class Medium
 *
 * @package App\Models
 */
class Medium extends DataModel
{
    /**
     * @var array
     */
    public static $dataFields = ['filename', 'format', 'mimetype', 'size', 'url'];
    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'filename',
        'size',
        'format',
        'mimetype',
        'url',
    ];
    /**
     * @var string
     */
    protected $table = 'media';

    /**
     * @param string $preset
     * @param null $lang
     *
     * @return string
     */
    public function url($preset = "icon", $lang = null)
    {
        $format = Config::get("media.presets.{$preset}.format");
        $lang = $lang ?? $this->_lang ?? Config::get('cms.langs')[0] ?? '';
        $friendly = str_slug(EntityContent::select("value")
                                          ->where("entity_id", $this->id)
                                          ->where("field", "title")
                                          ->where("lang", $lang)
                                          ->first()
            ?? 'media');

        return ("/media/{$this->id}/{$preset}/{$friendly}.{$format}");
    }
}
