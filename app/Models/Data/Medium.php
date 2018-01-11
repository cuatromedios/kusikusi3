<?php

namespace App\Models\Data;

use App\Models\Data;

class Medium extends Data
{
    public static $contentFields = ['title', 'description'];
    public static $dataFields = ['format', 'size'];
}
