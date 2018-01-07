<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Entity;

class EntitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $eHome = Entity::create([
            'model' => 'home',
            'created_by' => 'seeder',
            'updated_by' => 'seeder',
            'contents' => [
                'title' => 'Website title',
                'description' => 'The website description',
                'url' => '/',
                ['lang' => 'es', 'field' => 'summary', 'value' => 'El otro titulo'],
                ['lang' => 'en', 'field' => 'summary', 'value' => 'The other title']
            ]
        ]);

        $eSection = Entity::create([
            'model' => 'section',
            'parent' => $eHome['id'],
            'created_by' => 'seeder',
            'updated_by' => 'seeder'
        ]);

        $ePage = Entity::create([
            'model' => 'section',
            'parent' => $eSection['id'],
            'created_by' => 'seeder',
            'updated_by' => 'seeder'
        ]);

        $eMedia = Entity::create([
            'model' => 'media',
            'created_by' => 'seeder',
            'updated_by' => 'seeder'
        ]);
    }
}
