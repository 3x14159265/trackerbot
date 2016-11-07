<?php

namespace App\Http\Bot;
use App\Networks\NetworkFactory;
use App\Chat;
use App\Http\Bot\CommandFactory;

class HelpCommand extends Command
{

    public function execute($network, $chatId, $cmd, $params = '') {
        $handler = NetworkFactory::create($network);
        $chat = Chat::findByNetworkAndIdentifier($network, $chatId);

        $text = 'Hey '.$chat->name.'!'.PHP_EOL.PHP_EOL;
        $text .= 'Here\'s a list of available commands:'.PHP_EOL;
        $commands = CommandFactory::$commands;
        foreach($commands as $k=>$v) {
            if($k != '/help') $text .= $k.PHP_EOL;
        }

        $handler->sendText($chatId, $text);
    }

    public function help($network, $chatId) {
        $handler = NetworkFactory::create($network);
        $handler->sendText($chatId, 'This is the help command. Additional info should be shown here.');
    }

    /**
     * Here you can add your scheduled tasks.
     * This function is called every minute
     * @return void
     */
    public function schedule() {

    }
}
