<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DonationItem;

class DonationItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DonationItem::create([
            'id_category' => '1',
            'name' => 'baby clothes',
            'coins' => '75',
        ]); 

        DonationItem::create([
            'id_category' => '2',
            'name' => 'teddy bear',
            'coins' => '50',
        ]);   

        DonationItem::create([
            'id_category' => '3',
            'name' => 'kitchenware',
            'coins' => '60',
        ]);   
    }
}
