<?php

use Illuminate\Database\Seeder;
use App\Models\Entity;
use Illuminate\Support\Facades\Storage;

class SampleWebsiteSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $faker = Faker\Factory::create();
    $sections = 2;
    $pages_per_section = 2;
    $images_per_page = 2;
    for ($s = 0; $s < $sections; $s++) {
      $section = new Entity([
          "model" => "section",
          "parent_id" => "home"
      ]);
      $section->addContents([
          "title" => $faker->sentence,
          "summary" => $faker->paragraph
      ]);
      $section->save();
      for ($p = 0; $p < $pages_per_section; $p++) {
        $page = new Entity([
            "model" => "page",
            "parent_id" => $section->id
        ]);
        $page->addContents([
            "title" => $faker->sentence,
            "summary" => join("\n", $faker->paragraphs(5))
        ]);
        $page->save();
        for ($i = 0; $i < $images_per_page; $i++) {
          $image = new Entity([
              "model" => "medium",
              "parent_id" => 'media'
          ]);
          $image->save();

          $image_path = $faker->image(storage_path('media/original'), 640, 480, 'nature', true);
          $image_extension = explode(".", $image_path)[1];
          $dest_dir = storage_path('media/original/'.$image->id);
          mkdir(storage_path('media/original/'.$image->id));
          rename($image_path, $dest_dir.'/file.'.$image_extension);

          $image->addContents(["title" => $faker->sentence]);
          $image->medium()->save(new \App\Models\Medium([
              "size" => filesize($dest_dir.'/file.'.$image_extension),
              "format" => $image_extension
          ]));
          $page->addRelation(['id' => $image->id, 'kind' => 'medium', 'tags' => 'icon'.$i, 'position' => $i]);
        }
      }
    }
  }
}
