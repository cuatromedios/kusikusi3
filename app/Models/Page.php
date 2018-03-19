<?php

namespace App\Models;

use Cuatromedios\Kusikusi\Models\EntityData;

class Page extends EntityData
{
    public static $contentFields = ['title', 'description'];
    public static $dataFields = [];
}
