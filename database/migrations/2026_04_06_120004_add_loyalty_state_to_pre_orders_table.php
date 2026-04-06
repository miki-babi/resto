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
        if (! Schema::hasTable('pre_orders')) {
            return;
        }

        Schema::table('pre_orders', function (Blueprint $table): void {
            if (! Schema::hasColumn('pre_orders', 'loyalty_points_earned')) {
                $table->unsignedInteger('loyalty_points_earned')
                    ->default(0)
                    ->after('total_price');
            }

            if (! Schema::hasColumn('pre_orders', 'loyalty_points_applied')) {
                $table->boolean('loyalty_points_applied')
                    ->default(false)
                    ->after('loyalty_points_earned');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('pre_orders')) {
            return;
        }

        Schema::table('pre_orders', function (Blueprint $table): void {
            foreach (['loyalty_points_applied', 'loyalty_points_earned'] as $column) {
                if (Schema::hasColumn('pre_orders', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
