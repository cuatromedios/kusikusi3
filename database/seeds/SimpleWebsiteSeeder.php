<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Entity;
use App\Models\Medium;

class SimpleWebsiteSeeder extends Seeder
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
            'parent' => 'root',
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
            'updated_by' => 'seeder',
            'contents' => [
                'title' => 'Section title'
            ]
        ]);

        $ePage = Entity::create([
            'model' => 'page',
            'parent' => $eSection['id'],
            'created_by' => 'seeder',
            'updated_by' => 'seeder',
            'contents' => [
                'title' => 'Page title'
            ]
        ]);

        $eMedia = Entity::create([
            'model' => 'container',
            'parent' => 'root',
            'created_by' => 'seeder',
            'updated_by' => 'seeder',
            'name' => 'Media Container'
        ]);
        for ($i = 0; $i < 5; $i++) {
            $eMedium = Entity::create([
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


    }
}
