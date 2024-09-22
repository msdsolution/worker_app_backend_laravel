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
                'Authorization' => 'key=' . $this->serverKey,
                'Content-Type' => 'application/json',
            ],
            'json' => $notification,
        ]);

        return json_decode($response->getBody(), true);
    }
}