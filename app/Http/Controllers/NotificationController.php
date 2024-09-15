<?php

namespace App\Http\Controllers;

use App\Events\NotificationEvent;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    /**
     * Notify a single client.
     *
     * @param int $id Client ID
     * @return JsonResponse
     */
    public function notifySingleClient(int $id): JsonResponse
    {
        // Dispatch the NotificationEvent for a single client
        NotificationEvent::dispatch($id);

        return response()->json(
            ["status" => "Notification job dispatched for client ID " . $id],
            202
        );
    }

    /**
     * Notify selected clients.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function notifyForClientsSelected(Request $request): JsonResponse
    {
        $clientIds = $request->input("client_id", []);

        // Dispatch the NotificationEvent for selected clients
        NotificationEvent::dispatch(null, $clientIds);

        // Return immediate response
        return response()->json(
            ["status" => "Notification job dispatched for selected clients"],
            202
        );
    }

    /**
     * Send custom messages to selected clients.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function sendCustomMessageForClientsSelected(
        Request $request
    ): JsonResponse {
        $clientIds = $request->input("client_id", []);
        $message = $request->input("message", "");

        NotificationEvent::dispatch(null, $clientIds, $message);

        return response()->json(
            ["status" => "Custom message job dispatched for selected clients"],
            202
        );
    }
}


