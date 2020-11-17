<?php

use Illuminate\Database\Seeder;

class WarehousesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('warehouses')->delete();
        DB::table('warehouses')->insert([
            'name' => '01-cagua',
            'estado' => 'Aragua',
            'ciudad' => 'Cagua',
            'direccion' => 'unknow',
            'created_at' => now()
        ]);

        DB::table('user_warehouse')->delete();
        DB::table('user_warehouse')->insert([
            'user_id' => 1,
            'warehouse_id' => 1,
            'created_at' => now()
        ]);
    }
}
