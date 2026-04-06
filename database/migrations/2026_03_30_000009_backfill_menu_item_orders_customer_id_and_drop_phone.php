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
        if (! Schema::hasTable('menu_item_orders')) {
            return;
        }

        if (! Schema::hasColumn('menu_item_orders', 'customer_id')) {
            Schema::table('menu_item_orders', function (Blueprint $table): void {
                $table->foreignId('customer_id')
                    ->nullable()
                    ->constrained()
                    ->nullOnDelete();
            });
        }

        if (Schema::hasColumn('menu_item_orders', 'phone')) {
            $phones = DB::table('menu_item_orders')
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

                DB::table('menu_item_orders')
                    ->whereNull('customer_id')
                    ->where('phone', $phone)
                    ->update(['customer_id' => $customerId]);
            }

            Schema::table('menu_item_orders', function (Blueprint $table): void {
                $table->dropColumn('phone');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('menu_item_orders')) {
            return;
        }

        if (! Schema::hasColumn('menu_item_orders', 'phone')) {
            Schema::table('menu_item_orders', function (Blueprint $table): void {
                $table->string('phone', 20)->nullable();
            });
        }
    }
};
