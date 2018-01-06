<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Entity;
use Ramsey\Uuid\Uuid;

class EntitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $home_id = Uuid::uuid4()->toString();
        Entity::create([
            '_id'   => $home_id,
            'model' => 'home',
            'created_by' => 'seeder',
            'updated_by' => 'seeder'
        ]);
        $section_id = Uuid::uuid4()->toString();
        Entity::create([
            '_id'   => $section_id,
            'model' => 'section',
            'parent' => $home_id,
            'created_by' => 'seeder',
            'updated_by' => 'seeder'
        ]);
        $page_id = Uuid::uuid4()->toString();
        Entity::create([
            '_id'   => $page_id,
            'model' => 'section',
            'parent' => $section_id,
            'created_by' => 'seeder',
            'updated_by' => 'seeder'
        ]);

        $media_id = Uuid::uuid4()->toString();
        Entity::create([
            '_id'   => $media_id,
            'model' => 'media',
            'created_by' => 'seeder',
            'updated_by' => 'seeder'
        ]);
    }
}
