<?php

namespace App\Models;

use Cuatromedios\Kusikusi\Models\EntityModel;

class Entity extends EntityModel
{

  /**
   * Create an 1:1 relation for every EntityModel in the app
   */
  public function user() {
    return $this->hasOne('App\Models\User', 'id');
  }
}
