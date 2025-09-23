<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('product_attribute_values', function (Blueprint $table) {
            $table->string('color_code')->nullable()->after('value'); // For color attributes (hex code)
            $table->string('image')->nullable()->after('color_code'); // For image attributes
            $table->integer('sort_order')->default(0)->after('image');
            $table->boolean('is_active')->default(true)->after('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_attribute_values', function (Blueprint $table) {
            $table->dropColumn([
                'color_code',
                'image',
                'sort_order',
                'is_active'
            ]);
        });
    }
};
