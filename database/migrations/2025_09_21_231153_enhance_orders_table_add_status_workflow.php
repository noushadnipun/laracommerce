<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EnhanceOrdersTableAddStatusWorkflow extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_orders', function (Blueprint $table) {
            // Order workflow fields
            $table->enum('order_status', ['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled', 'returned'])
                  ->default('pending')
                  ->after('delivery_status');
            
            $table->text('order_notes')->nullable()->after('note');
            $table->text('admin_notes')->nullable()->after('order_notes');
            $table->string('tracking_number')->nullable()->after('admin_notes');
            $table->string('shipping_carrier')->nullable()->after('tracking_number');
            $table->timestamp('shipped_at')->nullable()->after('shipping_carrier');
            $table->timestamp('delivered_at')->nullable()->after('shipped_at');
            $table->timestamp('cancelled_at')->nullable()->after('delivered_at');
            
            // Customer communication
            $table->boolean('email_sent')->default(false)->after('cancelled_at');
            $table->boolean('sms_sent')->default(false)->after('email_sent');
            $table->timestamp('last_notification_sent')->nullable()->after('sms_sent');
            
            // Financial fields
            $table->decimal('tax_amount', 10, 2)->default(0)->after('total_amount');
            $table->decimal('discount_amount', 10, 2)->default(0)->after('tax_amount');
            $table->decimal('final_amount', 10, 2)->default(0)->after('discount_amount');
            
            // Indexes
            $table->index(['order_status', 'created_at']);
            $table->index(['payment_status', 'order_status']);
            $table->index('tracking_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_orders', function (Blueprint $table) {
            $table->dropIndex(['order_status', 'created_at']);
            $table->dropIndex(['payment_status', 'order_status']);
            $table->dropIndex('tracking_number');
            
            $table->dropColumn([
                'order_status',
                'order_notes',
                'admin_notes',
                'tracking_number',
                'shipping_carrier',
                'shipped_at',
                'delivered_at',
                'cancelled_at',
                'email_sent',
                'sms_sent',
                'last_notification_sent',
                'tax_amount',
                'discount_amount',
                'final_amount'
            ]);
        });
    }
}