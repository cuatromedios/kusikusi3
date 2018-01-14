<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'contents';

    /**
     * The primary key
     */
    protected $primaryKey = 'entity_id';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Indicates  the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get the Entity that owns the content.
     */
    public function entity()
    {
        return $this->belongsTo('App\Models\Entity');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'entity_id', 'lang', 'field', 'value'
    ];


    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'entity_id',
    ];

}
