<?php

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->delete();
        DB::table('categories')->insert([
            [
                'name' => 'Alimentos secos',
                'created_at' => now()
            ],
            [
                'name' => 'Aderesos',
                'created_at' => now()
            ],
            [
                'name' => 'Bebidas',
                'created_at' => now()
            ],
            [
                'name' => 'Alimentos refrigerados',
                'created_at' => now()
            ]
        ]);
    }
}
