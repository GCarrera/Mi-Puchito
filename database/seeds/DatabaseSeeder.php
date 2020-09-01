<?php

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $this->call([
            StatesTableSeeder::class,
            CitiesTableSeeder::class,
            SectorsTableSeeder::class,
            UsersTableSeeder::class,
            CategoriesTableSeeder::class,
            EnterprisesTableSeeder::class,
            WarehousesTableSeeder::class,
            InventoriesTableSeeder::class,
            BankAccountsSeeder::class,
            DolarsTableSeeder::class,
            PisoVentasTableSeeder::class
        ]);

    }
}
