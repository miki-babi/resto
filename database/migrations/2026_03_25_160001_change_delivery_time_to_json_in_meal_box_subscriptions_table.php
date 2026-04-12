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
        Schema::table('meal_box_subscriptions', function (Blueprint $table) {
            $table->json('delivery_time_tmp')->nullable()->after('delivery_time');
        });

        DB::statement(
            'UPDATE `meal_box_subscriptions`
             SET `delivery_time_tmp` = JSON_ARRAY(CAST(`delivery_time` AS CHAR))
             WHERE `delivery_time` IS NOT NULL'
        );

        Schema::table('meal_box_subscriptions', function (Blueprint $table) {
            $table->dropColumn('delivery_time');
        });

        if (DB::getDriverName() === 'mysql') {
            DB::statement(
                'ALTER TABLE `meal_box_subscriptions`
                 CHANGE `delivery_time_tmp` `delivery_time` JSON NULL'
            );
        } else {
            Schema::table('meal_box_subscriptions', function (Blueprint $table) {
                $table->renameColumn('delivery_time_tmp', 'delivery_time');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('meal_box_subscriptions', function (Blueprint $table) {
            $table->time('delivery_time_tmp')->nullable()->after('delivery_time');
        });

        DB::statement(
            "UPDATE `meal_box_subscriptions`
             SET `delivery_time_tmp` = JSON_UNQUOTE(JSON_EXTRACT(`delivery_time`, '$[0]'))
             WHERE `delivery_time` IS NOT NULL"
        );

        Schema::table('meal_box_subscriptions', function (Blueprint $table) {
            $table->dropColumn('delivery_time');
        });

        if (DB::getDriverName() === 'mysql') {
            DB::statement(
                'ALTER TABLE `meal_box_subscriptions`
                 CHANGE `delivery_time_tmp` `delivery_time` TIME NULL'
            );
        } else {
            Schema::table('meal_box_subscriptions', function (Blueprint $table) {
                $table->renameColumn('delivery_time_tmp', 'delivery_time');
            });
        }
    }
};
