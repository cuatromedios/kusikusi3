<?php

use App\Models\Entity;
use Illuminate\Database\Seeder;

/**
 * Class RootSeeder
 */
class RootSeeder extends Seeder
{
    /**
     * Run the root entity seed.
     *
     * @return void
     */
    public function run()
    {
        $root = new Entity ([
            "id"        => "root",
            "model"     => "root",
            "parent_id" => "",
        ]);
        $root->save();
    }
}
