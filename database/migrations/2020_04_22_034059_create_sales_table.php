<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code');
            // $table->string('type');
            $table->string('sub_total')->nullable();
            $table->string('iva')->nullable();
            $table->string('amount');
            $table->dateTime('dispatched', 0)->nullable();
            $table->string('payment_reference_code')->nullable();
            $table->string('confirmacion')->nullable();
            $table->integer('count_product');
            // $table->string('payment_capture');
            $table->string('delivery')->default('No');
            $table->string('stimated_time')->nullable();
            $table->unsignedInteger('user_id');
            $table->timestamps();

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
        Schema::dropIfExists('sales');
    }
}
