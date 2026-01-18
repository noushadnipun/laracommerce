<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('product_order_details', function (Blueprint $table) {
            $table->string('product_title')->nullable()->after('product_id');
            $table->string('product_code')->nullable()->after('product_title');
            $table->string('featured_image')->nullable()->after('product_code');
            $table->decimal('unit_price', 10, 2)->nullable()->after('qty');
            $table->string('currency', 8)->nullable()->after('unit_price');
            $table->decimal('line_total', 10, 2)->nullable()->after('currency');
            $table->string('brand_name')->nullable()->after('line_total');
            $table->string('category_name')->nullable()->after('brand_name');
        });
    }

    public function down(): void
    {
        Schema::table('product_order_details', function (Blueprint $table) {
            $table->dropColumn([
                'product_title','product_code','featured_image',
                'unit_price','currency','line_total','brand_name','category_name'
            ]);
        });
    }
};


