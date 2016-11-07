<?php

namespace App\Http\Bot;
use App\Networks\NetworkFactory;

use Crypt;
use App\Chat;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\App;

class APIKeyCommand extends Command
{
    public function execute($network, $chatId, $cmd, $params = '') {
        $handler = NetworkFactory::create($network);
        $params = empty($params) ? [] : explode(' ', $params);

        if(count($params) &&
            !(filter_var($params[0], FILTER_VALIDATE_URL)
                || $params[0] == '*'
                || $params[0] == 'renew')) {
            return $handler->sendText($chatId, 'Invalid url.');
        }

        $prefix = $handler->getPrefix();
        $key = $prefix.$chatId;
        $secret = $prefix.hash('sha256', uniqid().$chatId.':'.config('app.key'));
        try {
            $app = App::findByKey($key);
            $info = $handler->getInfo($chatId);
            $app->name = $info['name'];
            if(count($params) && $params[0] == 'renew') {
                 $app->api_secret = $secret;
            }
            $urls = $app->urls;
            if(count($params) == 1 && $params[0] != 'renew' && count($urls) == 1 && $urls[0] == '*') $urls = [];
            if(count($params) == 2 && $params[1] == 'delete') $urls = array_diff($urls, [$params[0]]);

            if(count($params) == 1 && $params[0] != 'renew' && $params[0] == '*') $urls = ['*'];
            else if(count($params) == 1 && $params[0] != 'renew') $urls[] = $params[0];
            $app->urls = $urls;
            $app->save();
        } catch (ModelNotFoundException $e) {
            $info = $handler->getInfo($chatId);
            $app = new App();
            $app->network = 'telegram';
            $app->name = $info['name'];
            $app->api_key = $key;
            $app->api_secret = $secret;
            $app->urls = ['*'];
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

        $text = "Here are your credentials:".PHP_EOL.PHP_EOL;
        $text .= "<b>api_key</b>: ".$app->api_key.PHP_EOL;
        $text .= "<b>api_secret</b>: ".$app->api_secret.PHP_EOL.PHP_EOL;
        $text .= '<b>Whitelisted domains</b>:'.PHP_EOL;
        foreach($app->urls as $url) {
            $text .= $url.PHP_EOL;
        }
        $text .= PHP_EOL;
        $text .= 'To whitelist domains, pass the domain (or * to whitelist all domains) to the api_key command, e.g.'.PHP_EOL;
        $text .= '/api_key http://example.com'.PHP_EOL;
        $text .= "To delete whitelisted domains, add 'delete' to the command, e.g.".PHP_EOL;
        $text .= '/api_key http://example.com delete';


        return $handler->sendText($chatId, $text);
    }

    public function help($network, $chatId) {
        $handler = NetworkFactory::create($network);
        $chat = Chat::findByNetworkAndIdentifier($network, $chatId);

        $text = 'Hey '.$chat->name.'!'.PHP_EOL;
        $text .= 'You can create and renew your API keys here.'.PHP_EOL;
        $text .= 'API keys are needed to pass information from outside into this chat.'.PHP_EOL;
        $text .= 'You can renew your API secret by typing:'.PHP_EOL;
        $text .= '/api_key renew'.PHP_EOL;
        $text .= 'To whitelist domains, pass the domain (or * to whitelist all domains) to the api_key command, e.g.'.PHP_EOL;
        $text .= '/api_key http://example.com'.PHP_EOL;
        $text .= "To delete whitelisted domains, add 'delete' to the command, e.g.".PHP_EOL;
        $text .= '/api_key http://example.com delete';

        return $handler->sendText($chatId, $text);
    }


    /**
     * Here you can add your scheduled tasks.
     * This function is called every minute
     * @return void
     */
    public function schedule() {

    }
}
