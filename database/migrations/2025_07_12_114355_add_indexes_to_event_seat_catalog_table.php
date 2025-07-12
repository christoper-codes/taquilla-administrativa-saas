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
        Schema::table('event_seat_catalog', function (Blueprint $table) {
            $table->index(['event_id', 'is_active'], 'idx_event_seat_event_active');
            $table->index(['seat_catalogue_id'], 'idx_event_seat_catalogue_id');
            $table->index(['seat_catalogue_status_id'], 'idx_event_seat_status');
            $table->index(['user_id'], 'idx_event_seat_user');
            $table->index(['sale_ticket_id'], 'idx_event_seat_sale_ticket');
        });

        Schema::table('seat_catalogues', function (Blueprint $table) {
            $table->index(['zone', 'is_active'], 'idx_seat_catalogue_zone_active');
            $table->index(['seat_type_id'], 'idx_seat_catalogue_seat_type');
        });

        Schema::table('event_seat_catalog_price_type', function (Blueprint $table) {
            $table->index(['event_seat_catalog_id', 'is_active'], 'idx_price_type_event_seat_active');
            $table->index(['price_type_id'], 'idx_price_type_id');
        });

        Schema::table('event_seat_catalog_promotion', function (Blueprint $table) {
            $table->index(['event_seat_catalog_id', 'is_active'], 'idx_promotion_event_seat_active');
            $table->index(['promotion_id'], 'idx_promotion_id');
        });

        Schema::table('seat_catalogue_statuses', function (Blueprint $table) {
            $table->index(['is_active'], 'idx_seat_status_active');
        });

        Schema::table('price_types', function (Blueprint $table) {
            $table->index(['is_active'], 'idx_price_types_active');
        });

        Schema::table('promotions', function (Blueprint $table) {
            $table->index(['is_active'], 'idx_promotions_active');
            $table->index(['promotion_type_id'], 'idx_promotions_type_id');
        });

        Schema::table('events', function (Blueprint $table) {
            $table->index(['serie_id', 'enabled_for_season_tickets', 'is_active'], 'idx_events_serie_optimized');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_seat_catalog', function (Blueprint $table) {
            $table->dropIndex('idx_event_seat_event_active');
            $table->dropIndex('idx_event_seat_catalogue_id');
            $table->dropIndex('idx_event_seat_status');
            $table->dropIndex('idx_event_seat_user');
            $table->dropIndex('idx_event_seat_sale_ticket');
        });

        Schema::table('seat_catalogues', function (Blueprint $table) {
            $table->dropIndex('idx_seat_catalogue_zone_active');
            $table->dropIndex('idx_seat_catalogue_seat_type');
        });

        Schema::table('event_seat_catalog_price_type', function (Blueprint $table) {
            $table->dropIndex('idx_price_type_event_seat_active');
            $table->dropIndex('idx_price_type_id');
        });

        Schema::table('event_seat_catalog_promotion', function (Blueprint $table) {
            $table->dropIndex('idx_promotion_event_seat_active');
            $table->dropIndex('idx_promotion_id');
        });

        Schema::table('seat_catalogue_statuses', function (Blueprint $table) {
            $table->dropIndex('idx_seat_status_active');
        });

        Schema::table('price_types', function (Blueprint $table) {
            $table->dropIndex('idx_price_types_active');
        });

        Schema::table('promotions', function (Blueprint $table) {
            $table->dropIndex('idx_promotions_active');
            $table->dropIndex('idx_promotions_type_id');
        });
    }
};
