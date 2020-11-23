<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UserSeeder::class);
        $defaultAdminData = array (
            'username' => 'Aws Al-Hadidi',
            'email' => 'aws.hadidi@yahoo.com',
            'password' => Hash::make('12345678'),
        );
        \App\Models\Admin::create($defaultAdminData);
    }
}
