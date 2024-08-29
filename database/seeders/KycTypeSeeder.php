<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class KycTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('kyc_types')->insert([
            [
                'uuid' => Str::random(8),
                'name' => 'Residence ID',
                'description' => 'ID card issued by your state government',
            ],
            [
                'uuid' => Str::random(8),
                'name' => 'Drivers License',
                'description' => 'ID card issued by your state government',
            ],
        ]);
    }
}
