<?php
namespace App\Services;

use App\Repositories\Interfaces\DemandeRepositoryInterface;
use App\Models\Demande;
use Illuminate\Support\Facades\DB;
use App\Models\Client;
use Exception;
use Illuminate\Support\Facades\Log;
use App\Notifications\DemandeAnnuleeNotification;
use App\Notifications\DemandeValideeNotification;
use Notification;

use App\Repositories\Interfaces\DetteRepositoryInterface;
class DemandeService
{
    protected $demandeRepository;
    protected $detteRepository;

    public function __construct(DemandeRepositoryInterface $demandeRepository, DetteRepositoryInterface $detteRepository)
    {
        $this->demandeRepository = $demandeRepository;
        $this->detteRepository = $detteRepository;
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


    public function verifierQuantitesDisponibles(Demande $demande)
{
    $quantitesDisponibles = [];

    foreach ($demande->articles as $article) {
        $quantiteSeuil = $article->seuil;
        $quantiteDisponible = $article->quantite - $article->pivot->quantity;

        if ($quantiteDisponible >= $quantiteSeuil) {
            $quantitesDisponibles[$article->id] = [
                'article_id' => $article->id,
                'quantite_disponible' => $quantiteDisponible - $quantiteSeuil,
            ];
        }
    }

    return $quantitesDisponibles;
}


public function annulerDemande(Demande $demande, $motif)
{
    $demande->etat = 'Annuler';
    $demande->save();

    $clientId = $demande->client_id;
    $client = Client::find($clientId);
    $clientUser = $client->user;
    Notification::route('mail', $clientUser->login) 
        ->notify(new DemandeAnnuleeNotification($motif));
}


public function validerDemande(Demande $demande)
{
    $demande->etat = 'Valider';
    $demande->save();
    $clientId = $demande->client_id;
    $client = Client::find($clientId);
    $clientUser = $client->user;

    // Créer une dette correspondante
    $this->creerDettePourDemande($demande);

    // Envoyer un message au client
    $client = $demande->client;
    Notification::route('mail', $clientUser->login)
        ->notify(new DemandeValideeNotification());
}

protected function creerDettePourDemande(Demande $demande)
{
    return DB::transaction(function () use ($demande) {
        // Assurez-vous que la demande a des articles
        if ($demande->articles->isEmpty()) {
            throw new Exception('La demande ne contient aucun article.');
        }

        $montantTotal = $demande->articles->sum(function ($article) {
            return $article->pivot->quantity * $article->pivot->price;
        });

        $detteData = [
            'client_id' => $demande->client_id, 
            'montant' => $montantTotal,
            
        ];

        $dette = $this->detteRepository->create($detteData);

        foreach ($demande->articles as $article) {
            $articleData = [
                'quantity' => $article->pivot->quantity,
                'price' => $article->pivot->price,
            ];

            
            $article->dettes()->attach($dette->id, $articleData);
            
            $article->quantite -= $article->pivot->quantity;
            $article->save();
        }

        return $dette;
    });
}



}
   // Calculer le montant total de la dette