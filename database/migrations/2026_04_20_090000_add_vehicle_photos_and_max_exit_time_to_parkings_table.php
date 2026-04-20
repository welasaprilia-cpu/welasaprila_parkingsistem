<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('parkings', function (Blueprint $table) {
            if (! Schema::hasColumn('parkings', 'entry_photo_path')) {
                $table->string('entry_photo_path')->nullable();
            }

            if (! Schema::hasColumn('parkings', 'exit_photo_path')) {
                $table->string('exit_photo_path')->nullable();
            }

            if (! Schema::hasColumn('parkings', 'max_exit_time')) {
                $table->timestamp('max_exit_time')->nullable()->after('exit_time');
            }
        });
    }

    public function down(): void
    {
        Schema::table('parkings', function (Blueprint $table) {
            if (Schema::hasColumn('parkings', 'entry_photo_path')) {
                $table->dropColumn('entry_photo_path');
            }

            if (Schema::hasColumn('parkings', 'exit_photo_path')) {
                $table->dropColumn('exit_photo_path');
            }

            if (Schema::hasColumn('parkings', 'max_exit_time')) {
                $table->dropColumn('max_exit_time');
            }
        });
    }
};
