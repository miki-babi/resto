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
        if (! Schema::hasTable('menu_item_orders')) {
            Schema::create('menu_item_orders', function (Blueprint $table) {
                $table->id();

                $table->foreignId('customer_id')
                    ->nullable()
                    ->constrained()
                    ->nullOnDelete();

                $table->foreignId('pickup_location_id')
                    ->constrained()
                    ->restrictOnDelete();

                $table->date('pickup_date');
                $table->time('pickup_time');

                $table->tinyInteger('pickup_day_of_week')->nullable();
                $table->tinyInteger('pickup_hour_slot')->nullable();
                $table->enum('pickup_period', ['day', 'night'])->nullable();

                $table->decimal('total_price', 10, 2);

                $table->enum('status', [
                    'pending',
                    'confirmed',
                    'preparing',
                    'ready',
                    'completed',
                    'cancelled',
                ])->default('pending');

                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('menu_item_orders')) {
            Schema::drop('menu_item_orders');
        }
    }
};
