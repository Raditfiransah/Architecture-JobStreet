<?php

namespace App\Console\Commands;

use App\Models\Lowongan;
use Illuminate\Console\Command;

class ExpireLowonganCommand extends Command
{
    protected $signature = 'lowongan:expire';

    protected $description = 'Mark active job postings as expired when their application deadline has passed.';

    public function handle(): int
    {
        $expiredCount = Lowongan::query()
            ->where('status', 'aktif')
            ->whereDate('batas_lamaran', '<', today())
            ->update(['status' => 'expired']);

        $this->info("{$expiredCount} lowongan expired.");

        return self::SUCCESS;
    }
}
