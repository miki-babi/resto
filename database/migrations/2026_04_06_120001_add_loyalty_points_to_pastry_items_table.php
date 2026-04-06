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
        if (! Schema::hasTable('pastry_items') || Schema::hasColumn('pastry_items', 'loyalty_points')) {
            return;
        }

        Schema::table('pastry_items', function (Blueprint $table): void {
            $table->unsignedInteger('loyalty_points')
                ->default(0)
                ->after('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('pastry_items') || ! Schema::hasColumn('pastry_items', 'loyalty_points')) {
            return;
        }

        Schema::table('pastry_items', function (Blueprint $table): void {
            $table->dropColumn('loyalty_points');
        });
    }
};
