<?php

namespace App\Models;

use Cuatromedios\Kusikusi\Models\DataModel;

class Medium extends DataModel
{
    protected $fillable = [
        'id', 'filename', 'size', 'format', 'mimetype', 'url'
    ];

    protected $table = 'media';
}
