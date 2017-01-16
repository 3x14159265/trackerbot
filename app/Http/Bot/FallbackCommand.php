<?php

namespace App\Http\Bot;
use App\Networks\NetworkFactory;
use App\Chat;

class FallbackCommand extends Command
{

    private $replies = [
        'My capacities are very limited.',
        'I\'m not a genius.',
        'What did you say?',
        'Maybe try a different vocabulary...',
        'Whatever...'
    ];

    private $welcome = [
        'You\'re welcome!',
        'No worries, that\'s my job 😉'
    ];

    public function execute($network, $chatId, $cmd, $params = '') {
        $handler = NetworkFactory::create($network);
        $cmd = $cmd.' '.$params;
        if(strpos($cmd, 'thanks') !== false
            || strpos($cmd, 'thx') !== false
            || strpos($cmd, 'thank you') !== false) {
                $reply = $this->welcome[rand(0, count($this->welcome)-1)];
                return $handler->sendText($chatId, $reply);
        } else {
            $reply = $this->replies[rand(0, count($this->replies)-1)];
            return $handler->sendText($chatId, $reply);
        }
    }

    public function help($network, $chatId) {
        $handler = NetworkFactory::create($network);
        $chat = Chat::findByNetworkAndIdentifier($network, $chatId);

        $text = 'Hey '.$chat->name.'!'.PHP_EOL;
        $text .= 'There\'s nothing to see here.';

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
