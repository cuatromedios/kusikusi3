<?php

namespace App\Models;

use Cuatromedios\Kusikusi\Models\DataModel;

class Medium extends DataModel
{
    protected $fillable = [
        'id', 'size', 'format'
    ];
}
