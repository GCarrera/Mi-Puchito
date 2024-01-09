<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('address_user_delivery_id');
            $table->unsignedInteger('sale_id');
            // $table->unsignedInteger('vehicle_id');
            // $table->unsignedInteger('travel_rate_id');
            $table->timestamps();

            $table->foreign('address_user_delivery_id')->references('id')->on('address_user_deliveries');
            $table->foreign('sale_id')->references('id')->on('sales');
            //$table->foreign('vehicle_id')->references('id')->on('vehicles');
            // $table->foreign('travel_rate_id')->references('id')->on('travel_rates');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deliveries');
    }
}
