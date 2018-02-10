<?php

namespace App\Models\Entities;

use Cuatromedios\Kusikusi\Models\Data;

class Home extends Data
{
    public static $contentFields = ['title', 'description'];
    public static $dataFields = [];
    public static function beforeSave($model)
    {
        // Home is always forced to name home
        $model['name'] = "Home";
        return $model;
    }
}
