<?php

namespace App\Models;

use Cuatromedios\Kusikusi\Models\DataModel;

class Medium extends DataModel
{
    public static $contentFields = ['title', 'description'];
    public static $dataFields = ['format', 'size'];
    protected $fillable = [
        'id', 'size', 'format'
    ];
}
