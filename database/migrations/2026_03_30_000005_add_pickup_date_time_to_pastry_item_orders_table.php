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
        Schema::table('pastry_item_orders', function (Blueprint $table) {
            if (! Schema::hasColumn('pastry_item_orders', 'pickup_date')) {
                $table->date('pickup_date')->nullable();
            }

            if (! Schema::hasColumn('pastry_item_orders', 'pickup_time')) {
                $table->time('pickup_time')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pastry_item_orders', function (Blueprint $table) {
            if (Schema::hasColumn('pastry_item_orders', 'pickup_date')) {
                $table->dropColumn('pickup_date');
            }

            if (Schema::hasColumn('pastry_item_orders', 'pickup_time')) {
                $table->dropColumn('pickup_time');
            }
        });
    }
};
