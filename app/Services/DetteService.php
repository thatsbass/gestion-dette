<?php 

namespace App\Services;

use App\Repositories\Interfaces\DetteRepositoryInterface;
use App\Repositories\Interfaces\PaiementRepositoryInterface;
use App\Models\Dette;
use App\Models\Article;
use App\Models\Paiement;
use Illuminate\Support\Facades\DB;
use Exception;

class DetteService
{
    protected $detteRepository;
    protected $paiementRepository;

    public function __construct(DetteRepositoryInterface $detteRepository, PaiementRepositoryInterface $paiementRepository)
    {
        $this->detteRepository = $detteRepository;
        $this->paiementRepository = $paiementRepository;
    }

    public function createDette(array $data): Dette
    {
        return DB::transaction(function () use ($data) {
            $dette = $this->detteRepository->create($data);
            
            // Attach articles to the dette
            if (isset($data['articles'])) {
                foreach ($data['articles'] as $articleData) {
                    $article = Article::find($articleData['articleId']);
                    if ($article) {
                        $article->dettes()->attach($dette->id, [
                            'quantity' => $articleData['qteVente'],
                            'price' => $articleData['prixVente'],
                        ]);
                        
                        // Update article stock
                        $article->quantite -= $articleData['qteVente'];
                        $article->save();
                    }
                }
            }
            
            // Handle payment if provided
            if (isset($data['paiement'])) {
                $this->addPayment($dette->id, $data['paiement']['montant']);
            }

            return $dette;
        });
    }

    public function updateDette(Dette $dette, array $data): Dette
    {
        return $this->detteRepository->update($dette, $data);
    }

    public function getAllDettes()
    {
        return $this->detteRepository->getAll();
    }

    public function getDettesByStatus($status)
    {
        return $this->detteRepository->getByStatus($status);
    }

    public function addPayment(int $detteId, float $montant)
    {
        $dette = $this->detteRepository->findById($detteId);
        if (!$dette) {
            throw new Exception('Dette not found');
        }

        $montantRestant = $dette->montant - $dette->paiements->sum('montant');
        if ($montant <= $montantRestant) {
            $paiement = $this->paiementRepository->create([
                'montant' => $montant,
                'date' => now(),
                'dette_id' => $detteId,
                'client_id' => $dette->client_id,
            ]);
            return $dette;
        } else {
            throw new Exception('Payment amount exceeds remaining debt');
        }
    }

    public function getDetteById(int $id)
    {
        return $this->detteRepository->findById($id);
    }

    public function getArticlesByDette(int $id)
    {
        $dette = $this->detteRepository->findById($id);
        if (!$dette) {
            throw new Exception('Dette not found');
        }
        return $dette->articles;
    }

    public function getPaymentsByDette(int $id)
    {
        return $this->paiementRepository->getByDette($id);
    }
}
