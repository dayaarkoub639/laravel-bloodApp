<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class InfobipService
{
    protected $client;
    protected $baseUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('services.infobip.base_url');
        $this->apiKey = config('services.infobip.api_key');
        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'headers' => [
                'Authorization' => 'App ' . $this->apiKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
        ]);
    }

    public function sendSms($to, $message)
    {
        try {
            $response = $this->client->post("https://$this->baseUrl/sms/2/text/advanced", [
                'json' => [
                    'messages' => [
                        [
                            'from' => 'Blood App',
                            'destinations' => [
                                ['to' => $to]
                            ],
                            'text' => $message,
                        ]
                    ]
                ]
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
