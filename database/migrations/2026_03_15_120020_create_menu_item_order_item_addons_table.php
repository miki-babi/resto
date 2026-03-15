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
        Schema::create('menu_item_order_item_addons', function (Blueprint $table) {
            $table->id();

            $table->foreignId('menu_item_order_item_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('menu_item_addon_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // Snapshots
            $table->string('name');
            $table->decimal('price', 10, 2);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_item_order_item_addons');
    }
};

