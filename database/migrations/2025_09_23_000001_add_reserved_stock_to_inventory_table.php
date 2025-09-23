<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('inventory') && !Schema::hasColumn('inventory', 'reserved_stock')) {
            Schema::table('inventory', function (Blueprint $table) {
                $table->unsignedInteger('reserved_stock')->default(0)->after('current_stock');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('inventory') && Schema::hasColumn('inventory', 'reserved_stock')) {
            Schema::table('inventory', function (Blueprint $table) {
                $table->dropColumn('reserved_stock');
            });
        }
    }
};



