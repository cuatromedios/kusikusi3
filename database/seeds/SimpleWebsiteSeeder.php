<?php

use Illuminate\Database\Seeder;
use Cuatromedios\Kusikusi\Models\Entity;
use Cuatromedios\Kusikusi\Models\Permission;
use App\Models\User;
use Hackzilla\PasswordGenerator\Generator\RequirementPasswordGenerator;

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

        $eUsers = Entity::create([
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
        print ("***************** \n");
        print ("* Admin password: ".$adminPassword)."\n";
        print ("***************** \n");
        $eAdminUser = Entity::create([
            'model' => 'user',
            'parent' => $eUsers['id'],
            'created_by' => 'seeder',
            'updated_by' => 'seeder',
            'data' => [
                'name' => 'Admin ',
                'email' => 'kusikusi',
                'password' => $adminPassword,
                'profile' => User::PROFILE_ADMIN
            ]
        ]);

        Permission::create([
            'user_id'   => $eAdminUser['id'],
            'entity_id' => 'root',
            'get'       => 'any',
            'post'      => 'any',
            'patch'     => 'any',
            'delete'    => 'any'
        ]);

        $eAdminUser->relations()->attach($eHome['id'], ['kind' => 'home', 'position' => 0]);

    }
}
