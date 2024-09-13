<?php

namespace App\Services\Messaging;

interface MessagingServiceInterface
{
    public function sendMessage(string $phoneNumber, string $message);
}
