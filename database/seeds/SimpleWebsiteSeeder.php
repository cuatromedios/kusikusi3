<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Entity;
use Cuatromedios\Kusikusi\Models\EntityContent;

class SimpleWebsiteSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $faker = Faker\Factory::create();
    $homeEntity = new Entity([
        "model" => 'home',
        "id" => 'home',
        "name" => ucfirst('Home'),
        "parent_id" => 'root',
    ]);
    $homeEntity->addContents(["title" => "The home", "summary" => "The home summary"]);
    $homeEntity->save();


    $adminEntity = new Entity([
        "model" => "user",
        "parent_id" => $homeEntity->id
    ]);
    $adminEntity->addContents(["cv" => "The user CV", "bio" => "My bio"]);
    $adminEntity->addRelation(['id' => $homeEntity->id, 'kind' => 'home', 'tags' => '1,2,3', 'depth'=>3, 'position'=>4]);
    $homeEntity->addRelation(['id' => $adminEntity->id, 'kind' => 'like', 'tags' => '4,5,6', 'depth'=>0, 'position'=>0]);

    $adminUser = new User([
        "name" => "Admin",
        "email" => "admin",
        "password" => "admin",
        "profile" => User::PROFILE_ADMIN,
      ]);
    $adminEntity->user()->save($adminUser);
    $adminEntity->save();

    $section = new Entity([
        "model" => "media",
        "id" => "media",
        "parent_id" => "root"
    ]);
    $section->save();

    for ($s = 1; $s <= 3; $s++) {
      $section = new Entity([
          "model" => "section",
          "id" => "section_".$s,
          "parent_id" => "home"
      ]);
      $section->addContents(["title" => "Section {$s}", "summary" => "The summary of section {$s}"]);
      $section->save();
      for ($p = 1; $p <= 3; $p++) {
        $page = new Entity([
            "model" => "page",
            "id" => "page_{$s}_{$p}",
            "parent_id" => $section->id
        ]);
        //$page->addContents(["title" => "Page {$s}-{$p}", "summary" => "The summary of page {$p} of section {$s}"]);
        $page->addContents([
            "title" => $faker->name,
            "summary" => $faker->address
        ]);
        $page->save();
        for ($i = 1; $i <= 2; $i++) {
          $image = new Entity([
              "model" => "medium",
              "id" => "image_{$s}_{$p}_{$i}",
              "parent_id" => 'media'
          ]);
          $image->addContents(["title" => "Image {$s}-{$p}-{$i}"]);
          $image->medium()->save(new \App\Models\Medium([
              "size" => rand(1000,9999),
              "format" => "jpg"
          ]));
          $image->save();
          $section->addRelation(['id' => $image->id, 'kind' => 'medium', 'tags' => 'icon'.$i, 'position' => $i]);
          $page->addRelation(['id' => $image->id, 'kind' => 'medium', 'tags' => 'icon'.$i, 'position' => $i]);
        }
        $page->addRelation(['id' => $homeEntity->id, 'kind' => 'home', 'tags' => []]);
      }
    }

    $pages = Entity::select('entities.id', 'model', 'parent_id')
         ->ofModel('page')
         ->childOf('section_3')
         ->withContents('title', 'summary')
         ->orderByContents('title' )
         ->withMedia('icon1')
         ->get()
         ->compact();

    print(json_encode($pages, JSON_PRETTY_PRINT));

    $page = Entity::select('id')
        ->ancestorOf('page_3_2')
        ->withContents('title', 'summary')
        ->get()
        ->compact();



  }
}
