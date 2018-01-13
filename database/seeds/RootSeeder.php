<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Entity;
use App\Models\Medium;

class RootSeeder extends Seeder
{
    /**
     * Run the root entity seed.
     *
     * @return void
     */
    public function run()
    {
        $eHome = Entity::create([
            'id' => 'root',
            'model' => 'root',
            'name' => 'Root',
            'created_by' => 'seeder',
            'updated_by' => 'seeder'
        ]);
    }
}
