<?php

namespace Database\Seeders;

use App\Models\Type;
use Illuminate\Database\Seeder;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Type::insert([
            'name' => 'Peralatan Mandi'
        ]);

        Type::insert([
            'name' => 'Makanan'
        ]);

        Type::insert([
            'name' => 'Minuman'
        ]);
    }
}
