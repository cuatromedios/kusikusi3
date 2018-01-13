<?php

namespace App\Models\Data;

use App\Models\Data;

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
