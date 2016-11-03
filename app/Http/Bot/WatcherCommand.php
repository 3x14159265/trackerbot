<?php

namespace App\Http\Bot;
use App\Networks\NetworkFactory;
use App\Chat;
use App\App;
use App\Watcher;

class WatcherCommand implements Command
{
    /**
     * [execute description]
     * @param  string $network the network identifier
     * @param  string $chatId  the chat identifier according to the network
     * @param  string $cmd     the command string
     * @param  string $params
     * @return [type]          [description]
     */
    public function execute($network, $chatId, $cmd, $params = '') {
        $handler = NetworkFactory::create($network);
        $params = explode(' ', $params);

        if(!count($params)) {
            return $handler->sendText($chatId, 'No url given');
        } else if (filter_var($params[0], FILTER_VALIDATE_URL) === false) {
            return $handler->sendText($chatId, 'Invalid url.');
        } else if(count($params) == 2 && $params[1] == 'delete') {
            try {
                $chat = Chat::findByNetworkAndIdentifier($network, $chatId);
                Watcher::findByAppIdAndUrl($chat->app_id, $params[0])->delete();
            } catch (\Exception $e) {}
            return $handler->sendText($chatId, 'Watcher deleted.');
        } else {
            $status = $this->getStatus($params[0]);

            $chat = Chat::findByNetworkAndIdentifier($network, $chatId);
            $watcher = Watcher::firstOrNew([
                'app_id' => $chat->app_id,
                'url' => $params[0]
            ]);
            $watcher->status = $status[1];
            $watcher->save();

            $data = [
                'Code' => $status[1].PHP_EOL
            ];
            if(count($status) == 3) $data['Status'] = $status[2];

            return $handler->sendText($chatId, $data);
        }
    }

    /**
     * Here you can add your scheduled tasks.
     * This function is called every minute
     * @return void
     */
    public function schedule() {
        $watchers = Watcher::all();
        foreach($watchers as $watcher) {
            $last = $watcher->status >= 400 ? 'offline' : 'online';
            $status = $this->getStatus($watcher->url);
            $watcher->status = $status[1];
            $watcher->save();
            $current = $status[1] >= 400 ? 'offline' : 'online';
            if($last != $current) {
                $url = $watcher->url;
                $emoji = $current == 'online' ?  '✅' : '❌';
                $text = "$emoji Your site $url is $current!";
                $this->sendToChats($watcher->app_id, $text);
            }
        }
    }

    private function sendToChats($appId, $text) {
        $app = App::findOrFail($appId);
        $chats = Chat::findByApp($app);
        foreach($chats as $chat) {
            $network = NetworkFactory::create($app->network);
            $network->sendText($chat->identifier, $text);
        }
    }

    private function getStatus($url) {
        try {
            $status = get_headers($url, 1)[0];
            return $status = explode(' ', $status, 3);
        } catch (\Exception $e) {
            return $status = ['', 0, 'Unreachable'];
        }
    }
}
