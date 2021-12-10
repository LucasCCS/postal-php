<?php

namespace Postal;

use GuzzleHttp\RequestOptions;
use GuzzleHttp\Client as GuzzleClient;

class Client
{
    public function __construct($host, $serverKey)
    {
        $this->host = $host;
        $this->serverKey = $serverKey;
    }

    public function makeRequest($controller, $action, $parameters)
    {
        $url = sprintf('%s/api/v1/%s/%s', $this->host, $controller, $action);

        // Headers
        $headers = [
            'x-server-api-key' => $this->serverKey,
            'content-type' => 'application/json',
        ];

        // Make the body
        $client = new GuzzleClient();

        $response = $client->post($url, [
            'headers' => $headers,
            RequestOptions::JSON => $parameters // or 'json' => [...]
        ]);

        if ($response->getStatusCode() === 200) {
            $json = json_decode($response->getBody()->getContents());

            if ($json->status == 'success') {
                return $json->data;
            } else {
                if (isset($json->data->code)) {
                    throw new Error(sprintf('[%s] %s', $json->data->code, $json->data->message));
                } else {
                    throw new Error($json->data->message);
                }
            }
        }

        throw new Error('Couldn’t send message to API');
    }
}
