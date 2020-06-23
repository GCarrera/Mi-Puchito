<?php

use Illuminate\Database\Seeder;

class InventoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('inventories')->delete();
        DB::table('inventories')->insert([
            [
                'product_name' => 'Harina P.A.N 1 kg',
                'description' => 'Harina de maiz blanco',
                'quantity' => '6',
                'unit_type' => 'bulto',
                'qty_per_unit' => '24',
                'status' => '2',
                'total_qty_prod' => '144',
                'category_id' => '1',
                'warehouse_id' => '1',
                'enterprise_id' => '1',
                'created_at' => now()
            ],
            [
                'product_name' => 'Mayonesa Kraft 445 gr',
                'description' => 'Embutido de mayonesa',
                'quantity' => '6',
                'unit_type' => 'caja',
                'qty_per_unit' => '6',
                'status' => '2',
                'total_qty_prod' => '36',
                'category_id' => '2',
                'warehouse_id' => '1',
                'enterprise_id' => '1',
                'created_at' => now()
            ],
            [
                'product_name' => 'Arroz Mary 1 kg',
                'description' => 'arroz',
                'quantity' => '6',
                'unit_type' => 'bulto',
                'qty_per_unit' => '24',
                'status' => '2',
                'total_qty_prod' => '144',
                'category_id' => '1',
                'warehouse_id' => '1',
                'enterprise_id' => '1',
                'created_at' => now()
            ],
            [
                'product_name' => 'Pasta Mary 1Kg',
                'description' => 'pasta',
                'quantity' => '6',
                'unit_type' => 'bulto',
                'qty_per_unit' => '24',
                'status' => '2',
                'total_qty_prod' => '144',
                'category_id' => '1',
                'warehouse_id' => '1',
                'enterprise_id' => '1',
                'created_at' => now()
            ],
            [
                'product_name' => 'Salchichas',
                'description' => 'de todo un poco',
                'quantity' => '2',
                'unit_type' => 'caja',
                'qty_per_unit' => '24',
                'status' => '2',
                'total_qty_prod' => '48',
                'category_id' => '4',
                'warehouse_id' => '1',
                'enterprise_id' => '1',
                'created_at' => now()
            ],
            [
                'product_name' => 'Refresco Glup 2 lt',
                'description' => 'Bebida gaseosa sabor cola',
                'quantity' => '6',
                'unit_type' => 'bulto',
                'qty_per_unit' => '6',
                'status' => '2',
                'total_qty_prod' => '36',
                'category_id' => '3',
                'warehouse_id' => '1',
                'enterprise_id' => '2',
                'created_at' => now()
            ],
            [
                'product_name' => 'Nestea Durazno 450 gr',
                'description' => 'Mezcla para té frio con azucar',
                'quantity' => '10',
                'unit_type' => 'caja',
                'qty_per_unit' => '30',
                'status' => '2',
                'total_qty_prod' => '300',
                'category_id' => '3',
                'warehouse_id' => '1',
                'enterprise_id' => '1',
                'created_at' => now()
            ],
            [
                'product_name' => 'Leche Completa 1 lt',
                'description' => 'Bedida lactea esterilizada de larga duracion',
                'quantity' => '10',
                'unit_type' => 'caja',
                'qty_per_unit' => '30',
                'status' => '2',
                'total_qty_prod' => '300',
                'category_id' => '3',
                'warehouse_id' => '1',
                'enterprise_id' => '1',
                'created_at' => now()
            ],
            [
                'product_name' => 'Pechugas de pollo 550 gr',
                'description' => 'Super pechugas de pollo marinadas Friko',
                'quantity' => '10',
                'unit_type' => 'caja',
                'qty_per_unit' => '30',
                'status' => '2',
                'total_qty_prod' => '300',
                'category_id' => '4',
                'warehouse_id' => '1',
                'enterprise_id' => '3',
                'created_at' => now()
            ],
        ]);
    }
}
