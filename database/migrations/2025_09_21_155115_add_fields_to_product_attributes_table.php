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
        Schema::table('product_attributes', function (Blueprint $table) {
            $table->string('type')->default('select')->after('name'); // select, color, text, image
            $table->string('display_type')->default('dropdown')->after('type'); // dropdown, radio, checkbox, color_swatch
            $table->boolean('is_required')->default(false)->after('display_type');
            $table->text('description')->nullable()->after('is_required');
            $table->integer('sort_order')->default(0)->after('description');
            $table->boolean('is_active')->default(true)->after('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_attributes', function (Blueprint $table) {
            $table->dropColumn([
                'type',
                'display_type', 
                'is_required',
                'description',
                'sort_order',
                'is_active'
            ]);
        });
    }
};
