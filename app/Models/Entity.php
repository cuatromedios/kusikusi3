<?php
namespace App\Models;

use Cuatromedios\Kusikusi\Models\EntityModel;

/**
 * Class Entity
 *
 * @package App\Models
 */
class Entity extends EntityModel
{

    /**
     * Create an 1:1 relation for every EntityData in the app
     */
    public function user()
    {
        return $this->hasOne('App\Models\User', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function medium()
    {
        return $this->hasOne('App\Models\Medium', 'id');
    }
}
