<?php

namespace App\Http\Bot;
use App\App;
use App\Chat;
use App\Networks\NetworkFactory;

abstract class Command
{

    abstract public function execute($network, $chatId, $cmd, $params = '');

    abstract public function help($network, $chatId);

    abstract public function schedule();

    protected function sendToChats($appId, $text)
    {
        $app = App::findOrFail($appId);
        $chats = Chat::findByApp($app);
        foreach ($chats as $chat) {
            $network = NetworkFactory::create($app->network);
            $network->sendText($chat->identifier, $text);
        }
    }
}
