<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_order_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->unsigned()
                  ->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->unsigned()
                  ->references('id')->on('product_orders')->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->unsigned()
                  ->references('id')->on('products')->onDelete('cascade');
            $table->json('attribute');
            $table->integer('qty'); 
            $table->integer('price'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_order_details');
    }
}
