<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventarios', function (Blueprint $table) {
            $table->bigIncrements('id');
            //$table->unsignedBigInteger('articulo_id');
            $table->string('name');
            $table->decimal('quanty')->nullable();
            $table->string('unit_type_mayor')->nullable();
            $table->string('unit_type_menor');
            $table->integer('qty_per_unit')->nullable();
            $table->string('status')->default('2')->nullable();
            $table->integer('total_qty_prod')->nullable();
            $table->unsignedInteger('inventory_id')->nullable();
            $table->unsignedBigInteger('piso_venta_id');
            $table->bigInteger('id_extra')->nullable();

            $table->timestamps();
            $table->foreign('inventory_id')->references('id')->on('inventories');
            $table->foreign('piso_venta_id')->references('id')->on('piso_ventas');
            //$table->foreign('articulo_id')->references('id')->on('articulos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventarios');
    }
}
