<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        foreach ([
            [
                'name' => 'Everyday Backpack',
                'slug' => 'everyday-backpack',
                'description' => 'A durable, lightweight backpack for work, travel, and everyday use.',
                'price' => '59.00',
            ],
            [
                'name' => 'Ceramic Coffee Set',
                'slug' => 'ceramic-coffee-set',
                'description' => 'A handmade-style ceramic mug and pour-over set for slow mornings.',
                'price' => '34.50',
            ],
            [
                'name' => 'Desk Lamp',
                'slug' => 'desk-lamp',
                'description' => 'An adjustable, warm LED desk lamp with a clean, modern profile.',
                'price' => '42.00',
            ],
            [
                'name' => 'Cotton Throw',
                'slug' => 'cotton-throw',
                'description' => 'A soft, breathable cotton throw for the sofa or the end of the bed.',
                'price' => '48.00',
            ],
            [
                'name' => 'Insulated Bottle',
                'slug' => 'insulated-bottle',
                'description' => 'A reusable stainless-steel bottle that keeps drinks cold or hot.',
                'price' => '24.00',
            ],
            [
                'name' => 'Weekly Planner',
                'slug' => 'weekly-planner',
                'description' => 'A simple undated planner to organize projects, habits, and weekly goals.',
                'price' => '18.00',
            ],
        ] as $product) {
            Product::query()->updateOrCreate(['slug' => $product['slug']], $product);
        }
    }
}
