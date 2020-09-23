<?php

use Illuminate\Database\Seeder;
use App\Dolar;

class DolarsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Dolar::create(['price' => 280000.00]);
        Dolar::create(['price' => 300000.00]);
    }
}
