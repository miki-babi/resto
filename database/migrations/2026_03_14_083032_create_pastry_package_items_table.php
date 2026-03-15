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
        Schema::create('pastry_package_items', function (Blueprint $table) {
    $table->id();
    $table->foreignId('pastry_package_id')->constrained()->cascadeOnDelete();
    $table->foreignId('pastry_item_id')->constrained()->cascadeOnDelete();
    $table->integer('amount')->default(1);
    $table->decimal('price', 10, 2)->nullable(); // package-specific price
    $table->boolean('show_price')->default(false); // show price to customer
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pastry_package_items');
    }
};
