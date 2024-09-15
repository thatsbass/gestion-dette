<?php

namespace App\Http\Controllers;

use App\Events\NotificationEvent;
use Illuminate\Http\Request;
use Log;
class NotificationController extends Controller
{
    public function notifySingleClient($id)
    {
        event(new NotificationEvent($id, "single"));
        return response()->json(
            ["message" => "Notification request is being processed."],
            200
        );
    }

    public function notifyForClientsSelected(Request $request)
    {
        $data = $request->validate([
            "client_id" => "required|array",
            "client_id.*.id" => "required|integer",
        ]);

        event(new NotificationEvent(null, "selected", $data["client_id"]));
        return response()->json(
            ["message" => "Notification request is being processed."],
            200
        );
    }

    public function sendCustomMessageForClientsSelected(Request $request)
    {
        $data = $request->validate([
            "client_id" => "required|array",
            "client_id.*.id" => "required|integer",
            "message" => "required|string",
        ]);
        event(
            new NotificationEvent(
                null,
                "custom",
                $data["client_id"],
                $data["message"]
            )
        );
        return response()->json(
            ["message" => "Notification request is being processed."],
            200
        );
    }
}
