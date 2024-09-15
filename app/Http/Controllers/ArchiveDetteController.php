<?php

namespace App\Http\Controllers;

use App\Repositories\ArchiveDetteRepository;
use Illuminate\Http\Request;

class ArchiveDetteController extends Controller
{
    protected $archiveDetteRepository;

    public function __construct(ArchiveDetteRepository $archiveDetteRepository)
    {
        $this->archiveDetteRepository = $archiveDetteRepository;
    }

    public function index()
    {
        return response()->json($this->archiveDetteRepository->getAll());
    }

    public function getByClient($clientId)
    {
        return response()->json(
            $this->archiveDetteRepository->getByClient($clientId)
        );
    }

    public function getById($id)
    {
        return response()->json($this->archiveDetteRepository->getById($id));
    }

    public function restoreByDate($date)
    {
        $this->archiveDetteRepository->restoreByDate($date);
        return response()->json(["message" => "Restoration complete"]);
    }

    public function restoreById($id)
    {
        $this->archiveDetteRepository->restoreById($id);
        return response()->json(["message" => "Restoration complete"]);
    }

    public function restoreByClient($clientId)
    {
        $this->archiveDetteRepository->restoreByClient($clientId);
        return response()->json(["message" => "Restoration complete"]);
    }
}
