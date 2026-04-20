<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('parkings', function (Blueprint $table) {
            if (! Schema::hasColumn('parkings', 'vehicle_number')) {
                $table->string('vehicle_number')->nullable()->after('plate_number');
            }

            if (! Schema::hasColumn('parkings', 'check_in')) {
                $table->timestamp('check_in')->nullable()->after('entry_time');
            }

            if (! Schema::hasColumn('parkings', 'check_out')) {
                $table->timestamp('check_out')->nullable()->after('exit_time');
            }
        });

        Schema::table('parking_payments', function (Blueprint $table) {
            if (! Schema::hasColumn('parking_payments', 'parking_id')) {
                $table->unsignedBigInteger('parking_id')->nullable()->after('id');
            }

            if (! Schema::hasColumn('parking_payments', 'total_bayar')) {
                $table->decimal('total_bayar', 10, 2)->default(0)->after('total_amount');
            }
        });

        $this->backfillParkingColumns();
        $this->backfillPaymentColumns();

        if (Schema::hasColumn('parking_payments', 'parking_id') && ! $this->foreignKeyExists('parking_payments', 'parking_id', 'parkings')) {
            Schema::table('parking_payments', function (Blueprint $table) {
                $table->foreign('parking_id')->references('id')->on('parkings')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        $foreignKeyName = $this->getForeignKeyName('parking_payments', 'parking_id', 'parkings');

        Schema::table('parking_payments', function (Blueprint $table) use ($foreignKeyName) {
            if (! empty($foreignKeyName)) {
                $table->dropForeign($foreignKeyName);
            }

            if (Schema::hasColumn('parking_payments', 'parking_id')) {
                $table->dropColumn('parking_id');
            }

            if (Schema::hasColumn('parking_payments', 'total_bayar')) {
                $table->dropColumn('total_bayar');
            }
        });

        Schema::table('parkings', function (Blueprint $table) {
            if (Schema::hasColumn('parkings', 'vehicle_number')) {
                $table->dropColumn('vehicle_number');
            }

            if (Schema::hasColumn('parkings', 'check_in')) {
                $table->dropColumn('check_in');
            }

            if (Schema::hasColumn('parkings', 'check_out')) {
                $table->dropColumn('check_out');
            }
        });
    }

    private function backfillParkingColumns(): void
    {
        if (
            Schema::hasColumn('parkings', 'plate_number') &&
            Schema::hasColumn('parkings', 'vehicle_number')
        ) {
            DB::table('parkings')
                ->whereNull('vehicle_number')
                ->update([
                    'vehicle_number' => DB::raw('plate_number'),
                ]);
        }

        if (
            Schema::hasColumn('parkings', 'entry_time') &&
            Schema::hasColumn('parkings', 'check_in')
        ) {
            DB::table('parkings')
                ->whereNull('check_in')
                ->update([
                    'check_in' => DB::raw('entry_time'),
                ]);
        }

        if (
            Schema::hasColumn('parkings', 'exit_time') &&
            Schema::hasColumn('parkings', 'check_out')
        ) {
            DB::table('parkings')
                ->whereNull('check_out')
                ->whereNotNull('exit_time')
                ->update([
                    'check_out' => DB::raw('exit_time'),
                ]);
        }
    }

    private function backfillPaymentColumns(): void
    {
        if (
            Schema::hasColumn('parking_payments', 'total_amount') &&
            Schema::hasColumn('parking_payments', 'total_bayar')
        ) {
            DB::table('parking_payments')
                ->where('total_bayar', 0)
                ->update([
                    'total_bayar' => DB::raw('total_amount'),
                ]);
        }

        if (
            Schema::hasColumn('parking_payments', 'parking_id') &&
            Schema::hasColumn('parking_payments', 'plate_number') &&
            Schema::hasColumn('parkings', 'plate_number')
        ) {
            $payments = DB::table('parking_payments')
                ->select('id', 'plate_number', 'entry_time', 'exit_time')
                ->whereNull('parking_id')
                ->get();

            foreach ($payments as $payment) {
                $parkingId = DB::table('parkings')
                    ->where('plate_number', $payment->plate_number)
                    ->when($payment->entry_time, fn ($query) => $query->where('entry_time', $payment->entry_time))
                    ->when($payment->exit_time, fn ($query) => $query->where('exit_time', $payment->exit_time))
                    ->value('id');

                if ($parkingId) {
                    DB::table('parking_payments')
                        ->where('id', $payment->id)
                        ->update(['parking_id' => $parkingId]);
                }
            }
        }
    }

    private function foreignKeyExists(string $table, string $column, string $referencedTable): bool
    {
        $connection = DB::connection();
        $driver = $connection->getDriverName();

        if ($driver === 'pgsql') {
            $schema = $connection->selectOne('SELECT current_schema() AS schema')->schema;

            return (bool) DB::selectOne(
                "SELECT tc.constraint_name
                 FROM information_schema.table_constraints AS tc
                 JOIN information_schema.key_column_usage AS kcu
                     ON tc.constraint_name = kcu.constraint_name
                     AND tc.constraint_schema = kcu.constraint_schema
                 JOIN information_schema.constraint_column_usage AS ccu
                     ON ccu.constraint_name = tc.constraint_name
                     AND ccu.constraint_schema = tc.constraint_schema
                 WHERE tc.constraint_type = 'FOREIGN KEY'
                   AND kcu.table_schema = ?
                   AND kcu.table_name = ?
                   AND kcu.column_name = ?
                   AND ccu.table_name = ?",
                [$schema, $table, $column, $referencedTable]
            );
        }

        if ($driver === 'mysql') {
            return (bool) DB::selectOne(
                "SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ? AND REFERENCED_TABLE_NAME = ?",
                [$table, $column, $referencedTable]
            );
        }

        return (bool) DB::selectOne(
            "SELECT tc.constraint_name
                 FROM information_schema.table_constraints AS tc
                 JOIN information_schema.key_column_usage AS kcu
                     ON tc.constraint_name = kcu.constraint_name
                     AND tc.constraint_schema = kcu.constraint_schema
                 JOIN information_schema.constraint_column_usage AS ccu
                     ON ccu.constraint_name = tc.constraint_name
                     AND ccu.constraint_schema = tc.constraint_schema
                 WHERE tc.constraint_type = 'FOREIGN KEY'
                   AND kcu.table_name = ?
                   AND kcu.column_name = ?
                   AND ccu.table_name = ?",
            [$table, $column, $referencedTable]
        );
    }

    private function getForeignKeyName(string $table, string $column, string $referencedTable): ?string
    {
        $connection = DB::connection();
        $driver = $connection->getDriverName();

        if ($driver === 'pgsql') {
            $schema = $connection->selectOne('SELECT current_schema() AS schema')->schema;

            $record = DB::selectOne(
                "SELECT tc.constraint_name
                 FROM information_schema.table_constraints AS tc
                 JOIN information_schema.key_column_usage AS kcu
                     ON tc.constraint_name = kcu.constraint_name
                     AND tc.constraint_schema = kcu.constraint_schema
                 JOIN information_schema.constraint_column_usage AS ccu
                     ON ccu.constraint_name = tc.constraint_name
                     AND ccu.constraint_schema = tc.constraint_schema
                 WHERE tc.constraint_type = 'FOREIGN KEY'
                   AND kcu.table_schema = ?
                   AND kcu.table_name = ?
                   AND kcu.column_name = ?
                   AND ccu.table_name = ?",
                [$schema, $table, $column, $referencedTable]
            );

            return $record?->constraint_name;
        }

        if ($driver === 'mysql') {
            $record = DB::selectOne(
                "SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ? AND REFERENCED_TABLE_NAME = ?",
                [$table, $column, $referencedTable]
            );

            return $record?->CONSTRAINT_NAME;
        }

        $record = DB::selectOne(
            "SELECT tc.constraint_name
                 FROM information_schema.table_constraints AS tc
                 JOIN information_schema.key_column_usage AS kcu
                     ON tc.constraint_name = kcu.constraint_name
                     AND tc.constraint_schema = kcu.constraint_schema
                 JOIN information_schema.constraint_column_usage AS ccu
                     ON ccu.constraint_name = tc.constraint_name
                     AND ccu.constraint_schema = tc.constraint_schema
                 WHERE tc.constraint_type = 'FOREIGN KEY'
                   AND kcu.table_name = ?
                   AND kcu.column_name = ?
                   AND ccu.table_name = ?",
            [$table, $column, $referencedTable]
        );

        return $record?->constraint_name;
    }
}
