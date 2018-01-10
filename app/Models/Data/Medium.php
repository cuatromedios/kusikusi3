<?php

namespace App\Models\Data;

use Illuminate\Database\Eloquent\Model;

class Medium extends Model
{
    /**
     * The table associated with the model will be always Entity.
     *
     * @var string
     */

    public static $contentFields = ['title', 'description'];
    public static $dataFields = ['format', 'size'];

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

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

}
