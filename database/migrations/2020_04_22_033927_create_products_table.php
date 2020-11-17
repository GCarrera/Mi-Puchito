<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('cost');
            // $table->string('wholesale_unit_type');
            // $table->string('wholesale_quantity_per_unit');
            // $table->string('wholesale_measure_unit');
            $table->string('iva_percent');
            $table->string('retail_margin_gain');
            // $table->string('retail_pvp');
            $table->string('retail_total_price');
            $table->string('retail_iva_amount');
            $table->string('image');
            $table->string('wholesale_margin_gain');
            // $table->string('wholesale_pvp');
            $table->string('wholesale_packet_price');
            $table->string('wholesale_total_individual_price');
            $table->string('wholesale_total_packet_price');
            $table->string('wholesale_iva_amount');
            $table->boolean('oferta')->default(0);
            $table->unsignedInteger('inventory_id');
            $table->timestamps();

            $table->foreign('inventory_id')->references('id')->on('inventories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
