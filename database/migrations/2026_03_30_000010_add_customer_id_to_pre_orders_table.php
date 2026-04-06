<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('pre_orders')) {
            return;
        }

        if (! Schema::hasColumn('pre_orders', 'customer_id')) {
            Schema::table('pre_orders', function (Blueprint $table): void {
                $table->foreignId('customer_id')
                    ->nullable()
                    ->constrained()
                    ->nullOnDelete();
            });
        }

        if (Schema::hasColumn('pre_orders', 'phone')) {
            $phones = DB::table('pre_orders')
                ->whereNull('customer_id')
                ->whereNotNull('phone')
                ->pluck('phone')
                ->map(fn ($phone): string => trim((string) $phone))
                ->filter()
                ->unique()
                ->values();

            foreach ($phones as $phone) {
                $customerId = DB::table('customers')
                    ->where('phone', $phone)
                    ->value('id');

                if (! $customerId) {
                    $now = now();

                    $customerId = DB::table('customers')->insertGetId([
                        'name' => $phone,
                        'phone' => $phone,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }

                DB::table('pre_orders')
                    ->whereNull('customer_id')
                    ->where('phone', $phone)
                    ->update(['customer_id' => $customerId]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Intentionally no-op to avoid accidental data loss in existing environments.
    }
};
