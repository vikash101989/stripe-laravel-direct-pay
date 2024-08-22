<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        for ($i = 0; $i < 10; $i++) {
            $product = new Product ([
                'name' => fake()->word,
                'description' => fake()->sentence,
                'amount' => fake()->randomFloat(2, 20, 100),
    
            ]);
            $product->save();
        }
    }
}
