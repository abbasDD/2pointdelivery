<?php

namespace Database\Seeders;

use App\Models\User;
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
        // Admin authentication created
        $user = User::factory()->create([
            'user_type' => 'admin',
            'client_enabled' => true,
            'helper_enabled' => true,
            'email' => 'admin@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('admin@123'),
            'is_active' => true,
            'is_updated' => true,
            'is_deleted' => false,
        ]);

        // Admin data created
        DB::table('admins')->insert([
            'user_id' => $user->id,
            'admin_type' => 'super',
            'first_name' => 'Super',
            'last_name' => 'Admin',
        ]);

        $this->command->info('Admin seeded!');

        // Client authentication created
        $client = User::factory()->create([
            'user_type' => 'user',
            'client_enabled' => true,
            'helper_enabled' => false,
            'email' => 'client@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('client@123'),
            'is_active' => true,
            'is_updated' => true,
            'is_deleted' => false,
        ]);

        // Client data created
        DB::table('clients')->insert([
            'user_id' => $client->id,
            'company_enabled' => true,
            'first_name' => 'Test',
            'last_name' => 'Client',
        ]);

        $this->command->info('Client seeded!');

        // Helper authentication created
        $helper = User::factory()->create([
            'user_type' => 'user',
            'client_enabled' => false,
            'helper_enabled' => true,
            'email' => 'helper@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('helper@123'),
            'is_active' => true,
            'is_updated' => true,
            'is_deleted' => false,
        ]);

        // Helper data created
        DB::table('helpers')->insert([
            'user_id' => $helper->id,
            'company_enabled' => true,
            'first_name' => 'Test',
            'last_name' => 'Helper',
        ]);

        $this->command->info('Helper seeded!');
    }
}
