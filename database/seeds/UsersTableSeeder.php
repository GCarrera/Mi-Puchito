<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();
        DB::table('people')->delete();
    	DB::table('people')->insert([
    		'dni' => '00000000',
    		'name' => 'Elon',
    		'lastname' => 'Musk',
    		'phone_number' => '04243372191',
    		'created_at' => now()
    	]);

    	DB::table('users')->insert([
    		'email' => 'elon@musk.com',
            'type' => 'admin',
    		'password' => bcrypt('admin123'),
    		'people_id' => 1,
    		'created_at' => now()
    	]);

        DB::table('people')->insert([
            'dni' => '26734165',
            'name' => 'Luis',
            'lastname' => 'Briceño',
            'phone_number' => '04149112456',
            'created_at' => now()
        ]);

        DB::table('users')->insert([
            'email' => 'siulbriceno20@gmail.com',
            'type' => 'customer',
            'password' => bcrypt('26734165'),
            'people_id' => 2,
            'created_at' => now()
        ]);
    }
}
