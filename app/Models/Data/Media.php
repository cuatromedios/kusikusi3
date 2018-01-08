<?php

namespace App\Models\Data;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    /**
     * The table associated with the model will be always Entity.
     *
     * @var string
     */

    public static $contentFields = ['title'];
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
