<?php

namespace App\Services;

use GuzzleHttp\Client;

class NotificationService
{
    protected $client;
    protected $serverKey;

    public function __construct()
    {
        $this->client = new Client();
        $this->serverKey = env('FIREBASE_SERVER_KEY');
    }

    public function sendNotification($deviceToken, $title, $body)
    {
        $url = 'https://fcm.googleapis.com/v1/projects/ratamithuro-e9039/messages:send';

        $notification = [
            'to' => $deviceToken,
            'notification' => [
                'title' => $title,
                'body' => $body,
            ],
        ];

        $response = $this->client->post($url, [
            'headers' => [
                'Authorization' => 'ya29.a0AcM612w3uDNqO9g8lm-Rb6j_pPRD8nnWUQsEKUfMdoFjk_8iEvh9ae4kBXcFr5XZUw6wgcbdHI7-otCykFgt55FvKCH2qhf1a8YB2h2bpWLQvCxM01rc-O0UEEugU4qycsi2iBQpC2V5lweAPQmyAiBChDI8Y6tG4iry85W5aCgYKAfYSARESFQHGX2MixDKzSr0xD2bm2ifeSUICQA0175',
                'Content-Type' => 'application/json',
            ],
            'json' => $notification,
        ]);

        return json_decode($response->getBody(), true);
    }
}