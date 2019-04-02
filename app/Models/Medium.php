<?php
namespace App\Models;

use Cuatromedios\Kusikusi\Models\DataModel;
use Cuatromedios\Kusikusi\Models\EntityContent;
use Illuminate\Support\Facades\Config;

class Medium extends DataModel
{
    public static $dataFields = ['filename', 'format', 'mimetype', 'size', 'url'];
    protected $fillable = [
        'id',
        'filename',
        'size',
        'format',
        'mimetype',
        'url',
    ];
    protected $table = 'media';

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
