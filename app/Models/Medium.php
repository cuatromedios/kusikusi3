<?php

namespace App\Models;

use Cuatromedios\Kusikusi\Models\EntityData;

class Medium extends EntityData
{
    public static $contentFields = ['title', 'description'];
    public static $dataFields = ['format', 'size'];
    protected $fillable = [
        'id', 'size', 'format'
    ];
}
