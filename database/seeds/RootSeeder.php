<?php

use Illuminate\Database\Seeder;
use \App\Models\Entity;

class RootSeeder extends Seeder
{
  /**
   * Run the root entity seed.
   *
   * @return void
   */

  public function run()
  {
    $root = new \App\Models\Root([
        "id" => "root"
    ]);
    $root->save();
  }
}
