<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RootSeeder::class);
        $this->call(AdminSeeder::class);
        $this->call(BaseWebsiteSeeder::class);
        $this->call(SampleWebsiteSeeder::class);
    }
}
