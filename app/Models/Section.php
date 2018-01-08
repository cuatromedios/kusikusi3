<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    /**
     * The table associated with the model will be always Entity.
     *
     * @var string
     */

    public static $contentFields = ['title', 'description'];
    public static $dataFields = [];

    /**
     * Indicates  the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get the Entity that owns the data.
     */
    public function entity()
    {
        return $this->belongsTo('App\Models\Entity');
    }

}
