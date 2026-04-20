<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('parkings', 'parking_spot_id')) {
            Schema::table('parkings', function (Blueprint $table) {
                $table->foreignId('parking_spot_id')
                    ->nullable()
                    ->constrained('parking_spots')
                    ->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('parkings', 'parking_spot_id')) {
            Schema::table('parkings', function (Blueprint $table) {
                $table->dropConstrainedForeignId('parking_spot_id');
            });
        }
    }
};
