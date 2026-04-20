<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('parkings', function (Blueprint $table) {
            $table->enum('vehicle_type', ['mobil', 'motor'])->default('mobil');
        });
    }

    public function down(): void
    {
        Schema::table('parkings', function (Blueprint $table) {
            $table->dropColumn('vehicle_type');
        });
    }
};

