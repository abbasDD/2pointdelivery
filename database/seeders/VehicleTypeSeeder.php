<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class VehicleTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('vehicle_types')->insert([
            'uuid' => Str::random(8),
            'name' => 'Car',
            'description' => 'Car with 4 wheels',
            'price_type' => 'km',
            'price' => 30
        ]);
        DB::table('vehicle_types')->insert([
            'uuid' => Str::random(8),
            'name' => 'Motorcycle',
            'description' => 'Motorcycle with 2 wheels',
            'price_type' => 'km',
            'price' => 10
        ]);
    }
}
