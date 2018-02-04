<?php

namespace App\Models\Entities;

use Cuatromedios\Kusikusi\Models\Data;

class Home extends Data
{
    public static $contentFields = ['title', 'description'];
    public static $dataFields = [];

    /**
     * Events.
     *
     * @var bool
     */
    public static function beforeSave($model)
    {
       $model['name'] = "Home";
        return $model;
    }

}
