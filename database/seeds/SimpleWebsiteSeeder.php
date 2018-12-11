<?php

use Illuminate\Database\Seeder;
use App\Models\Home;
use App\Models\User;
use App\Models\Entity;

class SimpleWebsiteSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $admin = new User([
      "name" => "Admin",
      "email" => "admin",
      "password" => "admin",
      "profile" => User::PROFILE_ADMIN,
      "contents" => [
          "cv" => "El nombre completo"
      ]
    ]);
    $admin->save();
    Entity::create([
        "model" => Home::modelId(),
        "id" => Home::modelId(),
        "name" => ucfirst(Home::modelId()),
        "parent_id" => 'root',
        "contents" => [
            "title" => "Home"
        ]
    ]);

    $home = Entity::find('home');
    $home->contents = [
        "summary" => "The home summary"
    ];
    $home->save();

    $user = User::with(['relatedEntity', 'relatedContents'])->get();
    $user->each->append('entity', 'contents');
    var_dump($user->toArray());

    $homes = Entity::with(['relatedContents'])->where(['model' => 'home'])->get();
    $homes->each->append('contents');
    var_dump($homes->toArray());


    /*
    $section = new \App\Models\Section([
        'parent_id' => $home->id
    ]);
    $section->save();
    \App\Models\Section::create([
       'parent_id' => $home->id
    ]);
    */
    /*$u1 = new App\Models\User([
        'name' => 'user'
    ]);
    $u1->save();
    print(" 1: ".$u1->id);
    $u1->contents()->saveMany([
       new EntityContent(['lang' => 'es', 'field' => 'title', 'value' => 'Home'])
    ]);
    */

    /*$u2 = new App\Models\Entity([
        'name' => 'user'
    ]);
    $u2->save();
    print(" 2: ".$u2->id);
    $u2->user()->create(["name" => "hugo"]);
    */


    /*
      $home = App\Models\Home([
          'id' => 'home',
          'model' => 'home',
          'created_by' => 'seeder',
          'updated_by' => 'seeder',
          'parent_id' => 'root',
          'contents' => [
              'title' => 'Website title',
              'description' => 'The website description',
              'url' => '/',
              ['lang' => 'es', 'field' => 'summary', 'value' => 'El otro titulo'],
              ['lang' => 'en', 'field' => 'summary', 'value' => 'The other title']
          ]
      ]);
    */
    /*
    $eSection = EntityBase::create([
        'model' => 'section',
        'parent' => $eHome['id'],
        'created_by' => 'seeder',
        'updated_by' => 'seeder',
        'contents' => [
            'title' => 'Section title',
            'description' => 'Section description',
            'url' => '/section',
        ]
    ]);

    $ePage = EntityBase::create([
        'model' => 'page',
        'parent' => $eSection['id'],
        'created_by' => 'seeder',
        'updated_by' => 'seeder',
        'contents' => [
            'title' => 'Page title',
            'description' => 'Section description',
            'url' => '/section/page',
        ]
    ]);

    $eMedia = EntityBase::create([
        'id' => 'media',
        'model' => 'container',
        'parent' => 'root',
        'created_by' => 'seeder',
        'updated_by' => 'seeder',
        'name' => 'Media Container'
    ]);

    for ($i = 0; $i < 5; $i++) {
        $eMedium = EntityBase::create([
            'model' => 'medium',
            'parent' => $eMedia['id'],
            'position' => $i,
            'created_by' => 'seeder',
            'updated_by' => 'seeder',
            'contents' => [
                'title' => 'Medium title '.$i
            ],
            'data' => [
                'format' => 'jpg',
                'size' => rand(0, 10000)
            ]
        ]);
        $ePage->relations()->attach($eMedium['id'], ['kind' => 'medium', 'position' => $i]);
    }

          $eUsers = EntityBase::create([
              'id' => 'users',
              'model' => 'container',
              'parent' => 'root',
              'created_by' => 'seeder',
              'updated_by' => 'seeder',
              'name' => 'Users Container'
          ]);

          $generator = new RequirementPasswordGenerator();
          $generator
              ->setLength(12)
              ->setOptionValue(RequirementPasswordGenerator::OPTION_UPPER_CASE, true)
              ->setOptionValue(RequirementPasswordGenerator::OPTION_LOWER_CASE, true)
              ->setOptionValue(RequirementPasswordGenerator::OPTION_NUMBERS, true)
              ->setOptionValue(RequirementPasswordGenerator::OPTION_SYMBOLS, true)
              ->setMinimumCount(RequirementPasswordGenerator::OPTION_LOWER_CASE, 2)
              ->setMinimumCount(RequirementPasswordGenerator::OPTION_NUMBERS, 2)
              ->setMinimumCount(RequirementPasswordGenerator::OPTION_SYMBOLS, 1)
              ->setMaximumCount(RequirementPasswordGenerator::OPTION_UPPER_CASE, 5)
              ->setMaximumCount(RequirementPasswordGenerator::OPTION_LOWER_CASE, 5)
              ->setMaximumCount(RequirementPasswordGenerator::OPTION_NUMBERS, 5)
              ->setMaximumCount(RequirementPasswordGenerator::OPTION_SYMBOLS, 2)
          ;
          $adminPassword = $generator->generatePassword();
          $adminUsername = 'admin';
          print ("***************** \n");
          print ("* Admin username: ".$adminUsername)."\n";
          print ("* Admin password: ".$adminPassword)."\n";
          print ("***************** \n");
          $eAdminUser = EntityBase::create([
              'model' => 'user',
              'name' => 'Admin',
              'parent' => $eUsers['id'],
              'created_by' => 'seeder',
              'updated_by' => 'seeder',
              'data' => [
                  'name' => 'Admin',
                  'email' => $adminUsername,
                  'password' => $adminPassword,
                  'profile' => User::PROFILE_ADMIN
              ]
          ]);

           Permission::create([
              'user_id'   => $eAdminUser['id'],
              'entity_id' => 'root',
              'read'       => 'any',
              'write'      => 'any'
          ]);

    $eAdminUser->relations()->attach($eHome['id'], ['kind' => 'home', 'position' => 0]);
    */
  }
}
