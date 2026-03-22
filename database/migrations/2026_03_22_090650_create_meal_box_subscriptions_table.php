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
       Schema::create('meal_box_subscriptions', function (Blueprint $table) {
    $table->id();
    $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
    $table->foreignId('meal_box_plan_id')->constrained()->cascadeOnDelete();

    $table->date('start_date');
    $table->date('end_date');

    $table->enum('status', ['active', 'paused', 'cancelled'])->default('active');

    $table->time('delivery_time')->nullable();
    $table->string('address');

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meal_box_subscriptions');
    }
};
