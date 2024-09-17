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

    public function index(Request $request)
    {
        $clientId = $request->query("client_id");
        $date = $request->query("date");

        $archivedDettes = $this->archiveDetteRepository->getAll(
            $clientId,
            $date
        );

        return response()->json($archivedDettes);
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
