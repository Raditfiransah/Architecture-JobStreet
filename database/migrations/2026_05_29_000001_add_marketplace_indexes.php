<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $duplicates = DB::table('proposal')
            ->selectRaw('MIN(id) as keep_id, user_id, proyek_id')
            ->groupBy('user_id', 'proyek_id')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        foreach ($duplicates as $duplicate) {
            DB::table('proposal')
                ->where('user_id', $duplicate->user_id)
                ->where('proyek_id', $duplicate->proyek_id)
                ->where('id', '!=', $duplicate->keep_id)
                ->delete();
        }

        Schema::table('proposal', function (Blueprint $table): void {
            $table->unique(['user_id', 'proyek_id'], 'proposal_user_id_proyek_id_unique');
            $table->index(['proyek_id', 'status'], 'proposal_proyek_id_status_index');
        });

        Schema::table('proyek', function (Blueprint $table): void {
            $table->index(['status', 'category'], 'proyek_status_category_index');
        });
    }

    public function down(): void
    {
        Schema::table('proposal', function (Blueprint $table): void {
            $table->dropUnique('proposal_user_id_proyek_id_unique');
            $table->dropIndex('proposal_proyek_id_status_index');
        });

        Schema::table('proyek', function (Blueprint $table): void {
            $table->dropIndex('proyek_status_category_index');
        });
    }
};
