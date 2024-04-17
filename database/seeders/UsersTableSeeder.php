<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'user_type' => 'admin',
                'account_type' => 'company',
                'email' => 'admin@gmail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('admin@123'),
                'is_active' => true,
                'is_updated' => false,
                'is_deleted' => false,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_type' => 'client',
                'account_type' => 'individual',
                'email' => 'client@gmail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('client@123'),
                'is_active' => true,
                'is_updated' => false,
                'is_deleted' => false,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_type' => 'helper',
                'account_type' => 'individual',
                'email' => 'helper@gmail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('helper@123'),
                'is_active' => true,
                'is_updated' => false,
                'is_deleted' => false,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
