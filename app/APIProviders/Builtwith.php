<?php

namespace App\APIProviders;

use GuzzleHttp\Client;
use App\Chat;
use App\Networks\NetworkFactory;

class Builtwith {

    public function __construct() {
        $this->client = new Client(['exceptions' => false]);
        $key = env('BUILTWITH_KEY');
        $this->endpoint = "https://api.builtwith.com/v11/api.json?KEY=$key&LOOKUP=";
    }

    public function sendInfo($app, $url, $filter = [], $meta = []) {
        $filter = array_map(function($v) {
            return strtolower($v);
        }, $filter);

        $endpoint = $this->endpoint.$url;

        $response = $this->client->get($endpoint);
        $result = json_decode((string) $response->getBody()->getContents(), true);

        $tech = [];
        foreach($result['Results'] as $r) {
            foreach($r['Result']['Paths'] as $p) {
                $technologies = $p['Technologies'];
                foreach($technologies as $t) {
                    $name = strtolower($t['Name']);
                    $tag = strtolower($t['Tag']);
                    if(in_array($name, $filter) && !in_array($name, $tech)) $tech[] = $name;
                    if(in_array($tag, $filter) && !in_array($name, $tech)) $tech[] = $name;
                }
            }
        }

        if(count($tech)) {
            $text = "I have found following technologies for $url:".PHP_EOL;
            foreach($tech as $t) $text .= $t.PHP_EOL;

            $chats = Chat::findByApp($app);
            foreach($chats as $chat) {
                $network = NetworkFactory::create($app->network);
                $network->sendText($chat->identifier, $text, $meta);
            }
        }
    }


}
