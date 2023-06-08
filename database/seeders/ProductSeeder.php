<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::insert([
            'name' => 'Sabun',
            'stock' => 10,
            'id_types' => 1
        ]);

        Product::insert([
            'name' => 'Shampoo',
            'stock' => 15,
            'id_types' => 1
        ]);

        Product::insert([
            'name' => 'Kopi',
            'stock' => 7,
            'id_types' => 3
        ]);

        Product::insert([
            'name' => 'Ayam Goreng',
            'stock' => 5,
            'id_types' => 2
        ]);
    }
}
