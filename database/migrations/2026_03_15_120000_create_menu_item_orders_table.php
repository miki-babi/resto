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
        Schema::create('menu_item_orders', function (Blueprint $table) {
            $table->id();

            $table->string('public_token')->unique();

            $table->foreignId('pickup_location_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->tinyInteger('pickup_day_of_week'); // 0 (Sun) - 6 (Sat)
            $table->tinyInteger('pickup_hour_slot'); // 1 - 12 (Ethiopian hour)
            $table->enum('pickup_period', ['day', 'night']);

            $table->decimal('total_price', 10, 2);

            $table->enum('status', [
                'pending',
                'preparing',
                'ready',
                'completed',
                'cancelled',
            ])->default('pending');

            $table->timestamps();

            $table->index(['pickup_location_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_item_orders');
    }
};

