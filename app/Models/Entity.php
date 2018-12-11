<?php

namespace App\Models;

use Cuatromedios\Kusikusi\Models\EntityModel;

class Entity extends EntityModel
{

  public function __construct(array $attributes = [])
  {
    parent::__construct($attributes);
  }

  /**
   * Create an 1:1 relation for every EntityModel in the app
   */
  public function user() {
    return $this->hasOne('App\Models\User', 'id');
  }
}
