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
        if (! Schema::hasTable('customers') || Schema::hasColumn('customers', 'loyalty_points_balance')) {
            return;
        }

        Schema::table('customers', function (Blueprint $table): void {
            $table->integer('loyalty_points_balance')
                ->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('customers') || ! Schema::hasColumn('customers', 'loyalty_points_balance')) {
            return;
        }

        Schema::table('customers', function (Blueprint $table): void {
            $table->dropColumn('loyalty_points_balance');
        });
    }
};
