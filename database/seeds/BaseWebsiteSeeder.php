<?php

use Illuminate\Database\Seeder;
use App\Models\Entity;
use App\Models\User;
use Cuatromedios\Kusikusi\Models\Permission;

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
    $homeEntity->addContents([
        "title" => "The website home",
        "summary" => "The website welcome message",
        "url" => "/"
    ]);
    $homeEntity->save();

    $mediaContainer = new Entity([
        "model" => 'media',
        "id" => 'media',
        "name" => 'Media container',
        "parent_id" => 'root'
    ]);
    $mediaContainer->save();

    $guestEntity = new Entity([
        "model" => "user",
        "parent_id" => 'users'
    ]);
    $user = new User([
        "name" => "Guest",
        "email" => "guest",
        "profile" => User::PROFILE_USER
    ]);
    $guestEntity->user()->save($user);
    $guestEntity->save();

    Permission::addPermission($user->id, 'home', Permission::ANY, Permission::NONE);
    Permission::addPermission($user->id, 'media', Permission::ANY, Permission::NONE);

  }
}
