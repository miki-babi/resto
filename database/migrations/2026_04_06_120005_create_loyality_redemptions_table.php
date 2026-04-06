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
        if (Schema::hasTable('loyality_redemptions')) {
            return;
        }

        Schema::create('loyality_redemptions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('customer_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('pre_order_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->foreignId('loyality_id')
                ->constrained('loyalities')
                ->cascadeOnDelete();
            $table->unsignedInteger('points_spent');
            $table->timestamp('redeemed_at');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['customer_id', 'redeemed_at']);
            $table->index(['loyality_id', 'redeemed_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loyality_redemptions');
    }
};
