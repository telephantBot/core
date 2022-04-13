<?php

namespace Telephantbot\Core;

use GuzzleHttp\Client;

class Telegram {
    
    private $botToken;

    public function __construct(string $botToken) {
        $this->botToken = $botToken;
        $this->client = new Client([
            'base_uri' => "https://api.telegram.org/bot$this->botToken/",
        ]);
    }

    public function setup(string $url)
    {
        $response = $this->client->request('GET', 'setWebhook', [
            'query' => ['url' => $url]
        ]);

        $result = json_decode($response->getBody()->getContents(), true);
        echo 'Description : ' . $result['description'] . PHP_EOL;
        if ($result['result'] == true) {
            echo 'Result : ok' . PHP_EOL;
        } else {
            echo 'Result : not ok' . PHP_EOL;
        }
    }

    public function getUpdate()
    {
        $contents = file_get_contents("php://input");
        $content = json_decode($contents, true);
        $content = $content['message'];

        if (!$content) {
            return [];
        }

        return [
            "chat_id" => $content["chat"]["id"],
            "text" => $content['text'],
        ];
    }

    public function sendMessage($array)
    {
        if (!$array) {
            return null;
        }
        $response = $this->client->request('GET', 'sendMessage', [
            'query' => $array
        ]);

        return json_encode($response->getBody()->getContents());
    }
}