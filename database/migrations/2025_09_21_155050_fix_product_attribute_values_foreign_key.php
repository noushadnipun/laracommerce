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
        // Check if the table exists and has the column
        if (!Schema::hasTable('product_attribute_values')) {
            return;
        }
        
        // Check if we have existing data
        $hasData = DB::table('product_attribute_values')->count() > 0;
        
        if (Schema::hasColumn('product_attribute_values', 'attributes_id')) {
            if ($hasData) {
                // If we have data, we need to handle it carefully
                // For now, let's just rename the column without foreign key constraint
                Schema::table('product_attribute_values', function (Blueprint $table) {
                    // Try to drop foreign key if it exists
                    try {
                        $table->dropForeign(['attributes_id']);
                    } catch (Exception $e) {
                        // Foreign key doesn't exist, continue
                    }
                    $table->renameColumn('attributes_id', 'attribute_id');
                });
            } else {
                // If no data, we can safely drop and recreate
                Schema::table('product_attribute_values', function (Blueprint $table) {
                    // Try to drop foreign key if it exists
                    try {
                        $table->dropForeign(['attributes_id']);
                    } catch (Exception $e) {
                        // Foreign key doesn't exist, continue
                    }
                    $table->dropColumn('attributes_id');
                    $table->foreignId('attribute_id')->after('id')
                          ->constrained('product_attributes')->onDelete('cascade');
                });
            }
        } elseif (!Schema::hasColumn('product_attribute_values', 'attribute_id')) {
            // If neither column exists, add the correct one
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
        Schema::table('product_attribute_values', function (Blueprint $table) {
            // Drop the new foreign key constraint
            $table->dropForeign(['attribute_id']);
            
            // Drop the new column
            $table->dropColumn('attribute_id');
            
            // Add back the old column
            $table->foreignId('attributes_id')->after('id')
                  ->references('id')->on('product_attributes')->onDelete('cascade');
        });
    }
};
