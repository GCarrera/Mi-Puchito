<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('product_name')->unique();
            $table->string('description');
            $table->string('quantity');
            $table->string('unit_type');
            $table->string('unit_type_menor')->nullable();
            $table->string('qty_per_unit');
            // $table->string('unit_measure');
            // $table->string('qty_per_unit_measure');
            // $table->string('qty_wholesale');
            $table->string('status')->default('2');
            $table->string('total_qty_prod');
            // $table->string('total_qty_prod_unit_measure');
            $table->unsignedInteger('category_id');
            $table->unsignedInteger('warehouse_id');
            $table->unsignedInteger('enterprise_id');
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('warehouse_id')->references('id')->on('warehouses');
            $table->foreign('enterprise_id')->references('id')->on('enterprises');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventories');
    }
}
