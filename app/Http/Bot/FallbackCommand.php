<?php

namespace App\Http\Bot;
use App\Networks\NetworkFactory;
use App\Chat;

class FallbackCommand extends Command
{
    public function execute($network, $chatId, $cmd, $params = '') {
        $handler = NetworkFactory::create($network);
        return $handler->sendText($chatId, 'Unknown command.');
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
