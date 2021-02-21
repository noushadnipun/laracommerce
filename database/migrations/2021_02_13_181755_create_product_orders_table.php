<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_code')->unique();
            $table->foreignId('user_id')->nullable()->unsigned()
                  ->references('id')->on('users')->onDelete('cascade');
            //$table->foreignId('user_shipment_id')->nullable()->unsigned()
                  //->references('id')->on('user_address_books')->onDelete('cascade');
            $table->string('customer_name')->nullable();
            $table->string('customer_phone')->nullable();
            $table->string('customer_address')->nullable();
            $table->string('customer_thana')->nullable();
            $table->string('customer_postal_code')->nullable();
            $table->string('customer_city')->nullable();
            $table->string('customer_country')->nullable();
            $table->double('total_amount');
            $table->double('shipping_cost')->nullable();
            $table->string('use_coupone')->nullable();
            $table->string('coupone_discount')->nullable();
            $table->string('currency')->nullable();
            $table->string('tran_id')->nullable();
            $table->string('note')->nullable();
            $table->string('payment_status')->default('Pending');
            $table->string('payment_type')->nullable();
            $table->string('delivery_status')->default('pending'); 
            $table->string('shiping_type')->nullable(); 
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
        Schema::dropIfExists('product_orders');
    }
}
