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
            $table->id();
            $table->integer('user_id')->unsigned();
            $table->string('category_id')->nullable();
            $table->integer('brand_id')->unsigned()->nullable();
            $table->string('title');
            $table->longtext('description')->nullable();
            $table->longtext('specification')->nullable();
            $table->string('short_description')->nullable();
            $table->string('slug')->unique();
            $table->string('code')->nullable();
            $table->integer('regular_price')->nullable();
            $table->integer('sale_price')->nullable();
            $table->integer('purchase_price')->nullable();
            $table->json('attribute')->nullable();
            $table->integer('refundable')->default(0);
            $table->string('shipping_type')->nullable();
            $table->string('shipping_cost')->nullable();
            $table->string('total_stock')->nullable();
            $table->string('current_stock')->nullable();
            $table->string('product_image')->nullable();
            $table->string('featured_image')->nullable();
            $table->string('visibilty')->default('1');
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
        Schema::dropIfExists('products');
    }
}
