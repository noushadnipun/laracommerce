<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if attributes_id column exists
        $columns = DB::select('SHOW COLUMNS FROM product_attribute_values');
        $hasAttributesId = false;
        $hasAttributeId = false;
        
        foreach ($columns as $column) {
            if ($column->Field === 'attributes_id') {
                $hasAttributesId = true;
            }
            if ($column->Field === 'attribute_id') {
                $hasAttributeId = true;
            }
        }
        
        if ($hasAttributesId && !$hasAttributeId) {
            // Column exists as attributes_id, rename it to attribute_id
            Schema::table('product_attribute_values', function (Blueprint $table) {
                $table->renameColumn('attributes_id', 'attribute_id');
            });
        } elseif (!$hasAttributesId && !$hasAttributeId) {
            // Neither column exists, create attribute_id
            Schema::table('product_attribute_values', function (Blueprint $table) {
                $table->foreignId('attribute_id')->after('id')
                      ->constrained('product_attributes')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Check current structure and revert if needed
        $columns = DB::select('SHOW COLUMNS FROM product_attribute_values');
        $hasAttributeId = false;
        
        foreach ($columns as $column) {
            if ($column->Field === 'attribute_id') {
                $hasAttributeId = true;
                break;
            }
        }
        
        if ($hasAttributeId) {
            Schema::table('product_attribute_values', function (Blueprint $table) {
                $table->renameColumn('attribute_id', 'attributes_id');
            });
        }
    }
};
