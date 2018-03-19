<?php

namespace App\Models;

use Cuatromedios\Kusikusi\Models\EntityData;

class Home extends EntityData
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
