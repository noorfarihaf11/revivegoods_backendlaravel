<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\DonationItem;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CategorySeeder::class,
            DonationItemSeeder::class,
        ]);
    }
}
