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

        if (! Schema::hasColumn('pre_orders', 'order_number')) {
            Schema::table('pre_orders', function (Blueprint $table): void {
                $table->string('order_number', 20)
                    ->nullable()
                    ->after('id');
            });
        }

        $preOrderIds = DB::table('pre_orders')
            ->where(function ($query): void {
                $query->whereNull('order_number')
                    ->orWhere('order_number', '');
            })
            ->orderBy('id')
            ->pluck('id');

        foreach ($preOrderIds as $id) {
            DB::table('pre_orders')
                ->where('id', $id)
                ->update([
                    'order_number' => 'PO-'.str_pad((string) $id, 6, '0', STR_PAD_LEFT),
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('pre_orders')) {
            return;
        }

        if (! Schema::hasColumn('pre_orders', 'order_number')) {
            return;
        }

        Schema::table('pre_orders', function (Blueprint $table): void {
            $table->dropColumn('order_number');
        });
    }
};
