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
        DB::table('resellers')->insert([
            'name' => 'Platform',
            'domain' => '*',
            'active' => true,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        DB::table('users')->insert([
            'reseller_id' => 1,
            'reseller' => true,
            'name' => 'System Owner',
            'email' => 'info@example.com',
            'password' => bcrypt('welcome'),
            'confirmed' => 1,
            'role' => 'admin',
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }
}
