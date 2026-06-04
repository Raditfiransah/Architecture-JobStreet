<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lowongan', function (Blueprint $table): void {
            $table->date('tanggal_mulai')->nullable()->after('deadline');
            $table->date('batas_lamaran')->nullable()->after('tanggal_mulai');
        });

        $this->backfillActivePeriod();
        $this->modifyStatusEnum(['aktif', 'nonaktif', 'ditutup', 'draft', 'expired']);
    }

    public function down(): void
    {
        DB::table('lowongan')
            ->where('status', 'expired')
            ->update(['status' => 'nonaktif']);

        $this->modifyStatusEnum(['aktif', 'nonaktif', 'ditutup', 'draft']);

        Schema::table('lowongan', function (Blueprint $table): void {
            $table->dropColumn(['tanggal_mulai', 'batas_lamaran']);
        });
    }

    private function backfillActivePeriod(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("
                UPDATE lowongan
                SET tanggal_mulai = COALESCE(tanggal_mulai, DATE(created_at), CURDATE()),
                    batas_lamaran = COALESCE(
                        batas_lamaran,
                        deadline,
                        DATE_ADD(COALESCE(DATE(created_at), CURDATE()), INTERVAL 30 DAY)
                    )
            ");

            return;
        }

        DB::statement("
            UPDATE lowongan
            SET tanggal_mulai = COALESCE(tanggal_mulai, DATE(created_at), DATE('now')),
                batas_lamaran = COALESCE(
                    batas_lamaran,
                    deadline,
                    DATE(COALESCE(created_at, DATE('now')), '+30 days')
                )
        ");
    }

    /**
     * MySQL stores enum values in the column definition, so adding/removing
     * statuses needs an ALTER. Other drivers used in tests can keep the column.
     */
    private function modifyStatusEnum(array $statuses): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        $quotedStatuses = collect($statuses)
            ->map(fn (string $status): string => "'{$status}'")
            ->implode(',');

        DB::statement("ALTER TABLE lowongan MODIFY status ENUM({$quotedStatuses}) NOT NULL DEFAULT 'aktif'");
    }
};
