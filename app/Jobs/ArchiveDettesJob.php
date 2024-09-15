<?php

namespace App\Jobs;

use App\Services\Archive\ArchiveServiceInterface;
use App\Models\Dette;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Log;

class ArchiveDettesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $archiveService = app(ArchiveServiceInterface::class);

        // Processus d'archivage...
        $dettesSoldees = Dette::whereRaw(
            "(montant - (SELECT COALESCE(SUM(montant), 0) FROM paiements WHERE dette_id = dettes.id)) = 0"
        )
            ->with("articles", "paiements")
            ->get();

        foreach ($dettesSoldees as $dette) {
            try {
                $archiveService->archiveDette($dette);
                $dette->delete();
            } catch (\Exception $e) {
                Log::error(
                    'Erreur lors de l\'archivage de la dette ID ' .
                        $dette->id .
                        ": " .
                        $e->getMessage()
                );
            }
        }
    }
}
