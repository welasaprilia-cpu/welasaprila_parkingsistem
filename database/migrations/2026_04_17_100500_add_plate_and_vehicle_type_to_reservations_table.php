<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->string('plate_number')->after('parking_spot_id');
            $table->enum('vehicle_type', ['car', 'motorcycle', 'truck'])->after('plate_number');
        });
    }

    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn(['plate_number', 'vehicle_type']);
        });
    }
};
