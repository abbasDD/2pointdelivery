<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ServiceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('service_types')->insert([
            [
                'uuid' => Str::random(32),
                'name' => 'Delivery',
                'description' => 'Delivery Service',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'uuid' => Str::random(32),
                'name' => 'Moving',
                'description' => 'Moving Service',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
