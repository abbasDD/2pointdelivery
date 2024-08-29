<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        // This will create admin, client and helper
        $this->call(UsersTableSeeder::class);
        $this->call(ServiceTypeSeeder::class);
        $this->call(SystemSettingSeeder::class);
        $this->call(FaqSeeder::class);
        $this->call(PrioritySettingSeeder::class);
        $this->call(VehicleTypeSeeder::class);
        $this->call(KycTypeSeeder::class);
    }
}
