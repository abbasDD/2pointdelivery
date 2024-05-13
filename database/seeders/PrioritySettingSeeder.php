<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PrioritySettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('priority_settings')->insert([
            [
                'name' => 'Standard',
                'description' => '2 Point Delivery Standard',
                'price' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Express',
                'description' => '2 Point Delivery Express',
                'price' => 50,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Same Day',
                'description' => '2 Point Delivery Same Day',
                'price' => 100,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
