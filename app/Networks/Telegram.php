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
            $name = '@'.$result['username'];
        } else if(array_key_exists('last_name', $result)) {
            $name = $result['last_name'];
        } else if(array_key_exists('first_name', $result)) {
            $name = $result['first_name'];
        } else {
            $name = $chatId;
        }

        return ['id' => $chatId, 'name' => $name];
    }

    /**
     * send an HTML formatted text to the client
     * @param  [type] $chatId chat id to send the message to
     * @param  [type] $text   the text to send, can be plain text or an array
     *                        which will be formatted
     * @return [type]         response of network
     */
    public function sendText($chatId, $text, $meta = [])
    {
        $formatted = $text;
        // if $text is an array, make key bold and add colon to separate from value
        // ['key' => 'value'] becomes <b>key</b>: value EOL
        if(gettype($text) === 'array') {
            $formatted = '';
            foreach($text as $k=>$v) {
                $formatted .= "<b>$k</b>: ".str_replace('_', '\_', $v).PHP_EOL;
            }
        }

        if(count($meta)) {
            $formatted .= PHP_EOL;
            $formatted .= $this->formatArray('', $meta);
        }

        $params = [
            'chat_id' => $chatId,
            'text' => $formatted,
            'parse_mode' => 'HTML',
        ];

        return $this->send($params);
    }

    /**
     * sends a formatted event to a chat
     * @param  App\App $app   the app the chat belongs to
     * @param  App\Chat $chat  the chat to send message to
     * @param  App\Event $event the event which will be sent
     * @return [type]        response of the network
     */
    public function sendEvent($app, $chat, $event)
    {
        $appName = $app->name;
        $text = '';
        if(str_replace('rt_', '', $event->type) == 'error') $text .= '❌ ';
        if(str_replace('rt_', '', $event->type) == 'event') $text .= '❕ ';
        $name = $event->event;
        $text .= "<b>$name</b>".PHP_EOL.PHP_EOL;
        if ($event->data) {
            $text .= '<b>Data</b>: '.PHP_EOL;
            $text .= $this->formatArray('', $event->data);
        }

        return $this->sendText($chat->identifier, $text);
    }

    private function send($params)
    {
        $token = $this->token;
        $params['disable_web_page_preview'] = true;
        $response = $this->client->post("https://api.telegram.org/bot$token/sendMessage", ['json' => $params]);
        $result = json_decode((string) $response->getBody()->getContents(), true);
        if (!$result['ok']) {
            return ['status' => $result['error_code'], 'response' => $result['description']];
        } else {
            return ['status' => 200, 'response' => 'sent'];
        }
    }

    private function formatArray($text, $data, $prefix = '') {
        foreach ($data as $k => $v) {
            if(gettype($v) === 'array') {
                $text = $this->formatArray($text, $v, $prefix.$k.' ➤ ');
            } else {
                $text .= "$prefix$k: $v".PHP_EOL;
            }
        }

        return $text;
    }
}
