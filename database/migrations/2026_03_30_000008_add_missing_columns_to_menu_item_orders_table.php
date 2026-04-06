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
            return;
        }

        Schema::table('menu_item_orders', function (Blueprint $table): void {
            if (! Schema::hasColumn('menu_item_orders', 'customer_id')) {
                $table->foreignId('customer_id')
                    ->nullable()
                    ->constrained()
                    ->nullOnDelete();
            }

            if (! Schema::hasColumn('menu_item_orders', 'pickup_location_id')) {
                $table->foreignId('pickup_location_id')
                    ->nullable()
                    ->constrained()
                    ->nullOnDelete();
            }

            if (! Schema::hasColumn('menu_item_orders', 'pickup_date')) {
                $table->date('pickup_date')->nullable();
            }

            if (! Schema::hasColumn('menu_item_orders', 'pickup_time')) {
                $table->time('pickup_time')->nullable();
            }

            if (! Schema::hasColumn('menu_item_orders', 'pickup_day_of_week')) {
                $table->tinyInteger('pickup_day_of_week')->nullable();
            }

            if (! Schema::hasColumn('menu_item_orders', 'pickup_hour_slot')) {
                $table->tinyInteger('pickup_hour_slot')->nullable();
            }

            if (! Schema::hasColumn('menu_item_orders', 'pickup_period')) {
                $table->enum('pickup_period', ['day', 'night'])->nullable();
            }

            if (! Schema::hasColumn('menu_item_orders', 'total_price')) {
                $table->decimal('total_price', 10, 2)->default(0);
            }

            if (! Schema::hasColumn('menu_item_orders', 'status')) {
                $table->enum('status', [
                    'pending',
                    'confirmed',
                    'preparing',
                    'ready',
                    'completed',
                    'cancelled',
                ])->default('pending');
            }

            if (! Schema::hasColumn('menu_item_orders', 'created_at')) {
                $table->timestamp('created_at')->nullable();
            }

            if (! Schema::hasColumn('menu_item_orders', 'updated_at')) {
                $table->timestamp('updated_at')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Intentionally no-op to avoid dropping data/columns from existing environments.
    }
};
