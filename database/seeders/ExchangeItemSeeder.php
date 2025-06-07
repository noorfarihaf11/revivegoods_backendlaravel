<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ExchangeItem;

class ExchangeItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ExchangeItem::create([
            'name' => 'Bamboo Utensil Set',
            'description' => 'Portable and reusable bamboo utensils with carrying case.',
            'image' => 'assets/images/bamboo.jpg',
            'coin_cost' => '250',
            'stock' => '6'
        ]); 

        ExchangeItem::create([
           'name' => 'Reusable Produce Bags (Set of 5)',
            'description' => 'Mesh bags for fruits and vegetables, machine washable.',
            'image' => 'assets/images/reusablebags.jpg',
            'coin_cost' => '150',
             'stock' => '6'
        ]);   

        ExchangeItem::create([
           'name' => 'Stainless Steel Water Bottle',
            'description' => 'Double-walled insulated bottle that keeps drinks cold for 24 hours.',
            'image' => 'assets/images/bottle.webp',
            'coin_cost' => '350',
             'stock' => '6'
        ]);   

        ExchangeItem::create([
           'name' => 'Organic Cotton Tote Bag',
            'description' => 'Durable, washable tote bag made from 100% organic cotton.',
            'image' => 'assets/images/totebag.webp',
            'coin_cost' => '200',
             'stock' => '6'
        ]);   

        ExchangeItem::create([
           'name' => 'Recycled Paper Notebook',
            'description' => 'Handcrafted notebook made from 100% recycled paper.',
            'image' => 'assets/images/notebook.webp',
            'coin_cost' => '100',
             'stock' => '6'
        ]);   
    }
}
