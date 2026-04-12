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
        Schema::create('delivery_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_id')->constrained()->cascadeOnDelete();
            $table->foreignId('menu_item_id')->nullable()->constrained()->nullOnDelete();
            $table->string('menu_item_title');
            $table->foreignId('selected_variant_id')->nullable()->constrained('menu_item_variants')->nullOnDelete();
            $table->string('selected_variant_name')->nullable();
            $table->decimal('selected_variant_price', 12, 2)->nullable();
            $table->integer('quantity');
            $table->decimal('price', 12, 2);
            $table->decimal('addons_unit_price', 12, 2)->default(0);
            $table->json('selected_addons')->nullable();
            $table->decimal('line_total_price', 12, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_items');
    }
};
