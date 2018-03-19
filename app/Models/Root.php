<?php

namespace App\Models;

use Cuatromedios\Kusikusi\Models\EntityData;

class Root extends EntityData
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
