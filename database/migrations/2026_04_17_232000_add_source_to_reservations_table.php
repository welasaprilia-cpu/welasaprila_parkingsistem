<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('reservations', 'source')) {
            Schema::table('reservations', function (Blueprint $table) {
                $table->string('source', 20)->default('manual')->after('parking_spot_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('reservations', 'source')) {
            Schema::table('reservations', function (Blueprint $table) {
                $table->dropColumn('source');
            });
        }
    }
};
