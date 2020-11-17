<?php

use Illuminate\Database\Seeder;

use App\BankAccount;

class BankAccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        BankAccount::create([
        	'bank' => 'banco exterior',
        	'code' => '0115',
        	'account_number' => '01150057971005190400',
        	'dni' => '23795320',
        	'name_enterprise' => 'Gibert Carrera',
        	'email_enterprise' => 'Gilbert@gmail.com',
        	'phone_enterprise' => '04243372191'

        ]);
    }
}
