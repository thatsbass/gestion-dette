<?php

namespace App\Services\Messaging;

interface MessagingServiceInterface
{
    public function sendMessage( $to, $message);
}
