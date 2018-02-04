<?php

namespace App\Models\Entities;

use Cuatromedios\Kusikusi\Models\Data;

class Page extends Data
{
    public static $contentFields = ['title', 'description'];
    public static $dataFields = [];
}
