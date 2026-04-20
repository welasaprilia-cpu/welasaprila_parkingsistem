<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('parking_spots', function (Blueprint $table) {
            if (!Schema::hasColumn('parking_spots', 'spot_number')) {
                $table->string('spot_number')->nullable()->after('id');
            }
            if (!Schema::hasColumn('parking_spots', 'floor')) {
                $table->integer('floor')->default(1)->after('spot_number');
            }
            if (!Schema::hasColumn('parking_spots', 'is_available')) {
                $table->boolean('is_available')->default(true)->after('status');
            }
        });

        $spots = DB::table('parking_spots')->get();
        foreach ($spots as $spot) {
            DB::table('parking_spots')->where('id', $spot->id)->update([
                'spot_number' => $spot->location,
                'is_available' => $spot->status === 'available',
            ]);
        }

        Schema::table('reservations', function (Blueprint $table) {
            if (!Schema::hasColumn('reservations', 'reserved_at')) {
                $table->timestamp('reserved_at')->nullable()->after('parking_spot_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('parking_spots', function (Blueprint $table) {
            if (Schema::hasColumn('parking_spots', 'spot_number')) {
                $table->dropColumn('spot_number');
            }
            if (Schema::hasColumn('parking_spots', 'floor')) {
                $table->dropColumn('floor');
            }
            if (Schema::hasColumn('parking_spots', 'is_available')) {
                $table->dropColumn('is_available');
            }
        });

        Schema::table('reservations', function (Blueprint $table) {
            if (Schema::hasColumn('reservations', 'reserved_at')) {
                $table->dropColumn('reserved_at');
            }
        });
    }
};
