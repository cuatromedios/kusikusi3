<?php

namespace App\Models;

use Cuatromedios\Kusikusi\Models\DataModel;

class Page extends DataModel
{
    public static $contentFields = ['title', 'description'];
    public static $dataFields = [];
}
