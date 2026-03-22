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
        Schema::create('meal_box_plan_items', function (Blueprint $table) {
    $table->id();
    $table->foreignId('meal_box_plan_id')->constrained()->cascadeOnDelete();
    $table->foreignId('meal_box_id')->constrained()->cascadeOnDelete();
    $table->unsignedTinyInteger('day_of_week'); // 1=Mon ... 7=Sun
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meal_box_plan_items');
    }
};
