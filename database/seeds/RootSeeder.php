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
        "id" => "root",
        "entity" => [
            "parent_id" => ""
        ]
    ]);
    $root->save();

    $book = new \App\Models\Book([
        "entity" =>  [
            "parent_id" =>  "the-category-id",
            "active" =>  false
        ]
    ]);
    $book->save();

    $book2 = \App\Models\Book::create([
      "entity" =>  [
          "parent_id" =>  "the-category-id-2",
          "active" =>  false
      ],
      "contents" => [
          ["field" => "title", "value" => "The Book"]
      ]
    ]);

    $book3 = \App\Models\Book::find($book2->id);

    $book3->entity = [
        "parent_id" => "nuevo"
    ];
    $book3->save();

    $books = \App\Models\Book::all();
    var_dump($books->toArray());



    /*$book = new \App\Models\Book();
    $book->save();

    \App\Models\Book::create();

    $book2 = new Entity();
    $book2->model = "book";
    $book2->save();*/
  }
}
