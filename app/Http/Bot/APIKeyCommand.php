<?php

namespace App\Http\Bot;
use App\Networks\NetworkFactory;

use Crypt;
use App\Chat;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\App;

class APIKeyCommand implements Command
{
    public function execute($network, $chatId, $cmd, $params = '') {
        $handler = NetworkFactory::create($network);
        $prefix = $handler->getPrefix();
        $key = $prefix.$chatId;
        $secret = $prefix.hash('sha256', uniqid().$chatId.':'.config('app.key'));
        try {
            $app = App::findByKey($key);
            $info = $handler->getInfo($chatId);
            $app->name = $info['name'];
            if(strlen($params) && $params == 'regenerate') {
                 $app->api_secret = $secret;
            }
            $app->save();
        } catch (ModelNotFoundException $e) {
            $info = $handler->getInfo($chatId);
            $app = new App();
            $app->network = 'telegram';
            $app->name = $info['name'];
            $app->api_key = $key;
            $app->api_secret = $secret;
            $app->save();

            $chat = Chat::firstOrNew([
                'app_id' => $app->id,
                'identifier' => $chatId
            ]);
            $chat->name = $info['name'];
            $chat->data = $info;
            $chat->save();
        }

        $data = [
            'api_key' => $app->api_key.PHP_EOL,
            'api_secret' => $app->api_secret.PHP_EOL
        ];

        return $handler->sendText($chatId, $data);
    }

    /**
     * Here you can add your scheduled tasks.
     * This function is called every minute
     * @return void
     */
    public function schedule() {

    }
}
