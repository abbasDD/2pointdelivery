<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DB::table('faqs')->insert([
            [
                'question' => 'Will you bring my items inside?',
                'answer' => 'Our delivery service includes up to 2 items in the delivery price, with additional items costing $5 per item. ',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'What hours does 2 point operate?',
                'answer' => '2 Point operates on a 24/7 basis, catering to your delivery needs at any hour of the day or night.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'How many items are include in the price of delivery?',
                'answer' => '2 items are included in the price of delivery with our 24/7 delivery service, with additional items costing £5 per item. ',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
