<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entity extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'entities';

    /**
     * The primary key
     */
    protected $primaryKey = '_id';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'created_at', 'updated_at', 'deleted_at',
    ];

    /**
     * Indicates  the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;
}
