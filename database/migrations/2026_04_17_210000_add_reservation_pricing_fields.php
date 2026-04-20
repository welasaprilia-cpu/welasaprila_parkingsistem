<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            if (!Schema::hasColumn('reservations', 'plate_number')) {
                $table->string('plate_number', 50)->nullable()->after('parking_spot_id');
            }
            if (!Schema::hasColumn('reservations', 'vehicle_type')) {
                $table->string('vehicle_type', 50)->nullable()->after('plate_number');
            }
            if (!Schema::hasColumn('reservations', 'duration')) {
                $table->integer('duration')->default(1)->after('vehicle_type');
            }
            if (!Schema::hasColumn('reservations', 'total_price')) {
                $table->integer('total_price')->default(0)->after('duration');
            }
            if (!Schema::hasColumn('reservations', 'reserved_at')) {
                $table->timestamp('reserved_at')->nullable()->after('parking_spot_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            if (Schema::hasColumn('reservations', 'total_price')) {
                $table->dropColumn('total_price');
            }
            if (Schema::hasColumn('reservations', 'duration')) {
                $table->dropColumn('duration');
            }
            if (Schema::hasColumn('reservations', 'vehicle_type')) {
                $table->dropColumn('vehicle_type');
            }
            if (Schema::hasColumn('reservations', 'plate_number')) {
                $table->dropColumn('plate_number');
            }
        });
    }
};
