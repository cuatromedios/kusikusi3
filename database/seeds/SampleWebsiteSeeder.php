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
    $sections = 1;
    $pages_per_section = 1;
    $images_per_page = 0;
    for ($s = 0; $s < $sections; $s++) {
      $section = new Entity([
          "model" => "section",
          "parent_id" => "home"
      ]);
      $section_title = $faker->sentence;
      $section_url =  "/".str_slug($section_title);
      $section->addContents([
          "title" => $section_title,
          "summary" => $faker->paragraph,
          "url" => $section_url
      ]);
      $section->save();
      for ($p = 0; $p < $pages_per_section; $p++) {
        $page = new Entity([
            "model" => "page",
            "parent_id" => $section->id
        ]);
        $page_title = $faker->sentence;
        $page->addContents([
            "title" => $faker->sentence,
            "summary" => join("\n", $faker->paragraphs(5)),
            "url" =>  $section_url ."/". str_slug($page_title)
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
          $tags = $i == 0 ? 'icon' : '';
          $page->addRelation(['id' => $image->id, 'kind' => 'medium', 'tags' => $tags, 'position' => $i]);
        }
      }
    }
  }
}
