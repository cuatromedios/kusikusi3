<?php

namespace App\Models;

use Cuatromedios\Kusikusi\Models\Data;

class Root extends Data
{
    public static $contentFields = [];
    public static $dataFields = [];

    /**
     * Events.
     *
     * @var bool
     */
    public static function beforeSave($model)
    {
       $model['id'] = "root";
       $model['parent'] = "";
       return $model;
    }

}
