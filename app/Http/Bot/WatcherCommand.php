<?php

namespace App\Http\Bot;

use App\Networks\NetworkFactory;
use App\Chat;
use App\App;
use App\Watcher;

class WatcherCommand extends Command
{
    /**
     * [execute description].
     *
     * @param string $network the network identifier
     * @param string $chatId  the chat identifier according to the network
     * @param string $cmd     the command string
     * @param string $params
     *
     * @return [type] [description]
     */
    public function execute($network, $chatId, $cmd, $params = '')
    {
        $handler = NetworkFactory::create($network);
        $params = empty($params) ? [] : explode(' ', $params);

        if (!count($params)) {
            $chat = Chat::findByNetworkAndIdentifier($network, $chatId);
            $watchers = Watcher::findByAppId($chat->app_id);
            $text = '';
            if(count($watchers)) {
                $text .= "Here's a list of your current active watchers:".PHP_EOL.PHP_EOL;
                foreach($watchers as $w) {
                    $text .= $w->url.PHP_EOL;
                }
                $text .= PHP_EOL;
            } else {
                $text = 'You currently don\'t have any active watchers.'.PHP_EOL.PHP_EOL;
            }
            $text .= 'To add a new watcher, pass the url to the watcher command, e.g.'.PHP_EOL;
            $text .= '/watcher http://example.com'.PHP_EOL;
            $text .= "To remove a watcher, add 'delete' to the command, e.g.".PHP_EOL;
            $text .= '/watcher http://example.com delete';

            return $handler->sendText($chatId, $text);
        } elseif (filter_var($params[0], FILTER_VALIDATE_URL) === false) {
            return $handler->sendText($chatId, 'Invalid url.');
        } elseif (count($params) == 2 && $params[1] == 'delete') {
            try {
                $chat = Chat::findByNetworkAndIdentifier($network, $chatId);
                Watcher::findByAppIdAndUrl($chat->app_id, $params[0])->delete();
            } catch (\Exception $e) {
            }

            return $handler->sendText($chatId, 'Watcher deleted.');
        } else {
            $status = $this->getStatus($params[0]);

            $chat = Chat::findByNetworkAndIdentifier($network, $chatId);
            $watcher = Watcher::firstOrNew([
                'app_id' => $chat->app_id,
                'url' => $params[0],
            ]);
            $watcher->status = $status[1];
            $watcher->save();

            return $handler->sendText($chatId, 'Watcher saved.');
        }
    }

    public function help($network, $chatId) {
        $handler = NetworkFactory::create($network);
        $chat = Chat::findByNetworkAndIdentifier($network, $chatId);

        $text = 'Hey '.$chat->name.'!'.PHP_EOL;
        $text .= 'This command let you watch URLs and sends you a notifications if the status of the given urls changes.';

        return $handler->sendText($chatId, $text);
    }

    /**
     * Here you can add your scheduled tasks.
     * This function is called every minute.
     */
    public function schedule()
    {
        $watchers = Watcher::all();
        foreach ($watchers as $watcher) {
            $last = $watcher->status >= 400 ? 'offline' : 'online';
            $status = $this->getStatus($watcher->url);
            $watcher->status = $status[1];
            $watcher->save();
            $current = $status[1] >= 400 ? 'offline' : 'online';
            if ($last != $current) {
                $url = $watcher->url;
                $emoji = $current == 'online' ?  '✅' : '❌';
                $text = "$emoji Your site $url is $current!";
                $data = [
                    'Alert' => $text,
                    'HTTP Code' => $status[1],
                    'HTTP Status' => $status[2]
                ];
                $this->sendToChats($watcher->app_id, $data);
            }
        }
    }

    private function getStatus($url)
    {
        try {
            $status = get_headers($url, 1)[0];

            return $status = explode(' ', $status, 3);
        } catch (\Exception $e) {
            return $status = ['', 0, 'Unreachable'];
        }
    }
}
