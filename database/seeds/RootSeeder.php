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
        ],
        "contents" => [
            "title" => "Libro 1"
        ]
    ]);
    $book->save();

    $book2 = \App\Models\Book::create([
      "entity" =>  [
          "parent_id" =>  "the-category-id-2",
          "active" =>  false
      ],
      "contents" => [
        "title" => "Libro 2 T1",
        "summary" => "Libro 2 S1",
        "es" => [
            "title" => "Libro 2 T1 en español"
        ]
      ]
    ]);
    $book2->save();

    $book2->contents = [
        "title" => "Libro 2 T2",
        "summary" => "Libro 2 S2",
        "description" => "Libro 2 D2",
        "fr" => [
            "title" => "Libro 2 T2 en francés"
        ]
    ];
    $book2->entity = [
        "parent_id" => "nuevo"
    ];
    $book2->save();

    // $books = \App\Models\Book::all();

    // var_dump($books->toArray());



    /*$book = new \App\Models\Book();
    $book->save();

    \App\Models\Book::create();

    $book2 = new Entity();
    $book2->model = "book";
    $book2->save();*/
  }
}
