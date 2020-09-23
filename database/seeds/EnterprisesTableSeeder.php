<?php

use Illuminate\Database\Seeder;

class EnterprisesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('enterprises')->delete();
        DB::table('enterprises')->insert([
            [
                'name' => 'Alimentos La Polar C.A',
                'created_at' => now()
            ],
            [
                'name' => 'Pepsico C.A',
                'created_at' => now()
            ],
            [
                'name' => 'Plumrose C.A',
                'created_at' => now()
            ],
        ]);
    }
}
