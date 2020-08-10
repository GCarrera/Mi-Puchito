<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTravelRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('travel_rates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('rate')->nullable();
            $table->string('stimated_time')->nullable();
            $table->unsignedInteger('sector_id');
            $table->timestamps();

            $table->foreign('sector_id')->references('id')->on('sectors');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('travel_rates');
    }
}
