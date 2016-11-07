<?php

namespace App\Networks;

use GuzzleHttp\Client;

class Slack implements Network {

    public function __construct() {
        $this->client = new Client(['exceptions' => false]);
    }

    public function getPrefix()
    {
        return 'sl_';
    }

    public function send($app, $chat, $event) {
        $text = '🌐 *App*: '.$app->name.PHP_EOL;
        $text .= '📌 *Event*: '.$event;
        if($data) {
            $attachment = [
                'fallback' => $text,
                'fields' => []
            ];
            foreach($data as $k=>$v) {
                $attachment['fields'][] = ['title' => $k, 'value' => $v, 'short' => true];
            }
        }

        $params = [
            'token' => $chat->data['bot']['bot_access_token'],
            'channel' => $chat->data['incoming_webhook']['channel_id'],
            'text' => $text
        ];

        if($data) {
            $params['attachments'] = json_encode([$attachment]);
        }

        $response = $this->client->post("https://slack.com/api/chat.postMessage", ['form_params' => $params]);
        \Log::debug((string)$response->getBody()->getContents());
        $result = json_decode((string)$response->getBody()->getContents(), true);
        \Log::debug($response->getBody()->getContents());
        if(!$result['ok'])
            return ['status' => $response->getStatusCode(), 'response' => $result['error']];
        else
            return ['status' => 201, 'response' => 'event_sent'];
    }
}
