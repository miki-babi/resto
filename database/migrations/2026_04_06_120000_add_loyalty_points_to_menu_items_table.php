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
        if (! Schema::hasTable('menu_items') || Schema::hasColumn('menu_items', 'loyalty_points')) {
            return;
        }

        Schema::table('menu_items', function (Blueprint $table): void {
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
        if (! Schema::hasTable('menu_items') || ! Schema::hasColumn('menu_items', 'loyalty_points')) {
            return;
        }

        Schema::table('menu_items', function (Blueprint $table): void {
            $table->dropColumn('loyalty_points');
        });
    }
};
