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
        Schema::create('pastry_item_orders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pastry_customer_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->enum('order_type', ['pickup', 'delivery']);

            // pickup
            $table->foreignId('pickup_location_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->tinyInteger('pickup_day_of_week')->nullable();
            $table->tinyInteger('pickup_hour_slot')->nullable();
            $table->enum('pickup_period', ['day', 'night'])->nullable();

            // delivery
            $table->string('delivery_phone')->nullable();
            $table->string('delivery_address')->nullable();

            $table->decimal('total_price', 10, 2);

            $table->enum('status', [
                'pending',
                'confirmed',
                'preparing',
                'ready',
                'completed',
                'cancelled'
            ])->default('pending');

            $table->enum('payment_status', [
                'pending',
                'paid',
                'failed',
                'refunded'
            ])->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pastry_item_orders');
    }
};
