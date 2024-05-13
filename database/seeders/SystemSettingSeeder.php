<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SystemSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('system_settings')->insert([
            [
                'key' => 'website_name',
                'value' => '2 Point Delivery',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'currency',
                'value' => 'usd',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'auto_assign_driver',
                'value' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'language',
                'value' => 'en',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'insurance',
                'value' => 'enabled',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
