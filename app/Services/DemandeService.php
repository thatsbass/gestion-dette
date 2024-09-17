<?php
namespace App\Services;

use App\Repositories\Interfaces\DemandeRepositoryInterface;
use App\Models\Demande;
use Illuminate\Support\Facades\DB;
use App\Models\Client;
use Exception;
use Illuminate\Support\Facades\Log;
use Notification;
class DemandeService
{
    protected $demandeRepository;

    public function __construct(DemandeRepositoryInterface $demandeRepository)
    {
        $this->demandeRepository = $demandeRepository;
    }

    public function createDemande($client, array $data): Demande
    {
        $client = Client::where('user_id', $client->id)->first();
        log::info('Le client est ' . $client);
        return DB::transaction(function () use ($client, $data) {
            $demande = $this->demandeRepository->create([
                'client_id' => $client->id,
                'montant' => $data['montant'],
                'etat' => $data['etat'],
            ]);

            $articles = [];
            foreach ($data['articles'] as $articleData) {
                $articles[$articleData['id']] = [
                    'quantity' => $articleData['quantity'],
                ];
            }

            $demande->articles()->attach($articles);

            return $demande;
        });
    }

    public function getDemandesByClient($clientId, $etat = null)
    {
        return $this->demandeRepository->getDemandesByClient($clientId, $etat);
    }

    public function getAllDemandes($etat = null)
    {
        return $this->demandeRepository->getAllDemandes($etat);
    }

    public function getNotifications()
    {
        $boutiquier = auth()->user();
    
        if ($boutiquier->role_id !== 2) {
            return response()->json(['message' => 'Accès refusé.'], 403);
        }
    
        $notifications = $boutiquier->notifications; 
    
        if ($notifications->isNotEmpty()) {
            // Mark all as read
            foreach ($notifications as $notification) {
                $notification->markAsRead();
            }
        }
    
        return $notifications;
    }
    
    

    public function findDemandeById($id)
    {
        return $this->demandeRepository->findById($id);
    }
}







   // public function sendRelance(Demande $demande)
    // {
    //     // Logique pour envoyer la relance après 2 jours
    // }