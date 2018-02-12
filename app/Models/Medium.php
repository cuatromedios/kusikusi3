<?php

namespace App\Models;

use Cuatromedios\Kusikusi\Models\Data;

class Medium extends Data
{
    public static $contentFields = ['title', 'description'];
    public static $dataFields = ['format', 'size'];
    protected $fillable = [
        'entity_id', 'size', 'format'
    ];
}
