<?php

use Illuminate\Database\Seeder;
use App\Models\Entity;

class BaseWebsiteSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $homeEntity = new Entity([
        "model" => 'home',
        "id" => 'home',
        "name" => ucfirst('Home'),
        "parent_id" => 'root'
    ]);
    $homeEntity->addContents(["title" => "The website home", "summary" => "The website welcome message"]);
    $homeEntity->save();

    $mediaContainer = new Entity([
        "model" => 'media',
        "id" => 'media',
        "name" => 'Media container',
        "parent_id" => 'root'
    ]);
    $mediaContainer->save();

  }
}
