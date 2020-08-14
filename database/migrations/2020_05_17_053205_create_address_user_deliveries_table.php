<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressUserDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('address_user_deliveries', function (Blueprint $table) {
            $table->increments('id');
            $table->string('details');
            $table->unsignedInteger('travel_rate_id')->nullable();
            $table->unsignedInteger('user_id');
            $table->timestamps();

            $table->foreign('travel_rate_id')->references('id')->on('travel_rates');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('address_user_deliveries');
    }
}
