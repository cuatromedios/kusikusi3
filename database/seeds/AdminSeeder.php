<?php

use App\Models\Entity;
use App\Models\User;
use Cuatromedios\Kusikusi\Models\Permission;
use Illuminate\Database\Seeder;

/**
 * Class AdminSeeder
 */
class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $usersContainer = new Entity([
            "model"     => 'users',
            "id"        => 'users',
            "name"      => 'Users',
            "parent_id" => 'root',
        ]);
        $usersContainer->save();
        $adminEntity = new Entity([
            "model"     => "user",
            "parent_id" => $usersContainer->id,
        ]);
        $user = new User([
            "name"     => "Admin",
            "username" => "admin",
            "password" => "admin",
            "profile"  => User::PROFILE_ADMIN,
        ]);
        $adminEntity->user()->save($user);
        $adminEntity->save();
        Permission::addPermission($user->id, 'root', Permission::ANY, Permission::ANY);
    }
}
