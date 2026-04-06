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
        if (! Schema::hasColumn('pastry_items', 'preorder_available')) {
            Schema::table('pastry_items', function (Blueprint $table) {
                $table->boolean('preorder_available')->default(false);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('pastry_items', 'preorder_available')) {
            Schema::table('pastry_items', function (Blueprint $table) {
                $table->dropColumn('preorder_available');
            });
        }
    }
};
