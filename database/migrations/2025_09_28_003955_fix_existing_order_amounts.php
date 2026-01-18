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
        // Fix existing orders where total_amount incorrectly includes shipping
        DB::statement("
            UPDATE product_orders 
            SET 
                total_amount = total_amount - COALESCE(shipping_cost, 0),
                final_amount = total_amount + COALESCE(shipping_cost, 0) + COALESCE(tax_amount, 0) - COALESCE(discount_amount, 0) - COALESCE(coupone_discount, 0)
            WHERE 
                shipping_cost > 0 
                AND total_amount > 0
                AND final_amount = total_amount
        ");
        
        // For orders where final_amount is 0 or null, calculate it properly
        DB::statement("
            UPDATE product_orders 
            SET 
                final_amount = total_amount + COALESCE(shipping_cost, 0) + COALESCE(tax_amount, 0) - COALESCE(discount_amount, 0) - COALESCE(coupone_discount, 0)
            WHERE 
                final_amount = 0 
                OR final_amount IS NULL
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration fixes data, so we can't easily reverse it
        // The original incorrect data is lost
    }
};