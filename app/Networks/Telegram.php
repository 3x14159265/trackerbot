<?php

namespace App\Networks;

use GuzzleHttp\Client;

class Telegram implements Network
{
    public function __construct()
    {
        $this->token = getenv('TELEGRAM_TOKEN');
        $this->client = new Client(['exceptions' => false]);
    }

    public function getPrefix()
    {
        return 'tg_';
    }

    public function getInfo($chatId)
    {
        $token = $this->token;
        $response = $this->client->post(
            "https://api.telegram.org/bot$token/getChat",
            ['json' => ['chat_id' => $chatId]]
        );
        $result = json_decode((string) $response->getBody()->getContents(), true)['result'];

        $name = '';
        if(array_key_exists('title', $result)) {
            $name = $result['title'];
        } else if(array_key_exists('username', $result)) {
            $name = $result['username'];
        } else if(array_key_exists('last_name', $result)) {
            $name = $result['last_name'];
        } else if(array_key_exists('first_name', $result)) {
            $name = $result['first_name'];
        } else {
            $name = $chatId;
        }

        return ['id' => $chatId, 'name' => $name];
    }

    public function sendText($chatId, $text)
    {
        $formatted = $text;
        if(gettype($text) === 'array') {
            $formatted = '';
            foreach($text as $k=>$v) {
                $formatted .= "*$k*: ".str_replace('_', '\_', $v);
            }
        }

        $params = [
            'chat_id' => $chatId,
            'text' => $formatted,
            'parse_mode' => 'Markdown',
        ];

        return $this->send($params);
    }

    public function sendEvent($app, $chat, $event, $data)
    {
        $appName = $app->name;
        // $text = "*$appName*".PHP_EOL;
        $text = "*$event*".PHP_EOL.PHP_EOL;
        if ($data) {
            $text .= '*Data*: '.PHP_EOL;
            // foreach ($data as $k => $v) {
            //     $text .= "$k: $v".PHP_EOL;
            // }
            $text .= $this->formatEvent('', $data);
        }

        return $this->sendText($chat->identifier, $text);
    }

    private function send($params)
    {
        $token = $this->token;
        $response = $this->client->post("https://api.telegram.org/bot$token/sendMessage", ['json' => $params]);
        $result = json_decode((string) $response->getBody()->getContents(), true);
        if (!$result['ok']) {
            return ['status' => $result['error_code'], 'response' => $result['description']];
        } else {
            return ['status' => 200, 'response' => 'sent'];
        }
    }

    private function formatEvent($text, $data, $prefix = '') {
        \Log::debug($data);
        \Log::debug('sub');
        foreach ($data as $k => $v) {
            \Log::debug('-----');
            \Log::debug($k);
            \Log::debug($v);
            \Log::debug($text);
            if(gettype($v) === 'array') {
                $text .= $this->formatEvent($text, $v, $k.'.');
            } else {
                $text .= "$prefix$k: $v".PHP_EOL;
            }
        }

        return $text;
    }
}
