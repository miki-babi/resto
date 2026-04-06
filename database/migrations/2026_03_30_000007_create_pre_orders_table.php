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
        if (! Schema::hasTable('pre_orders')) {
            Schema::create('pre_orders', function (Blueprint $table): void {
                $table->id();
                $table->enum('source_type', ['menu', 'cake']);
                $table->unsignedBigInteger('source_id')->nullable();
                $table->string('phone', 20);
                $table->foreignId('pickup_location_id')
                    ->nullable()
                    ->constrained()
                    ->nullOnDelete();
                $table->date('pickup_date')->nullable();
                $table->time('pickup_time')->nullable();
                $table->decimal('total_price', 10, 2)->default(0);
                $table->enum('status', [
                    'pending',
                    'confirmed',
                    'preparing',
                    'ready',
                    'completed',
                    'cancelled',
                ])->default('pending');
                $table->enum('payment_status', [
                    'pending',
                    'paid',
                    'failed',
                    'refunded',
                ])->default('pending');
                $table->json('items_summary')->nullable();
                $table->timestamps();

                $table->index(['source_type', 'source_id']);
                $table->index('status');
            });

            return;
        }

        Schema::table('pre_orders', function (Blueprint $table): void {
            if (! Schema::hasColumn('pre_orders', 'source_type')) {
                $table->enum('source_type', ['menu', 'cake'])->default('menu');
            }

            if (! Schema::hasColumn('pre_orders', 'source_id')) {
                $table->unsignedBigInteger('source_id')->nullable();
            }

            if (! Schema::hasColumn('pre_orders', 'phone')) {
                $table->string('phone', 20)->nullable();
            }

            if (! Schema::hasColumn('pre_orders', 'pickup_location_id')) {
                $table->foreignId('pickup_location_id')
                    ->nullable()
                    ->constrained()
                    ->nullOnDelete();
            }

            if (! Schema::hasColumn('pre_orders', 'pickup_date')) {
                $table->date('pickup_date')->nullable();
            }

            if (! Schema::hasColumn('pre_orders', 'pickup_time')) {
                $table->time('pickup_time')->nullable();
            }

            if (! Schema::hasColumn('pre_orders', 'total_price')) {
                $table->decimal('total_price', 10, 2)->default(0);
            }

            if (! Schema::hasColumn('pre_orders', 'status')) {
                $table->enum('status', [
                    'pending',
                    'confirmed',
                    'preparing',
                    'ready',
                    'completed',
                    'cancelled',
                ])->default('pending');
            }

            if (! Schema::hasColumn('pre_orders', 'payment_status')) {
                $table->enum('payment_status', [
                    'pending',
                    'paid',
                    'failed',
                    'refunded',
                ])->default('pending');
            }

            if (! Schema::hasColumn('pre_orders', 'items_summary')) {
                $table->json('items_summary')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('pre_orders')) {
            Schema::dropIfExists('pre_orders');
        }
    }
};
