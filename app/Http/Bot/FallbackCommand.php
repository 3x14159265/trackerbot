<?php

namespace App\Http\Bot;
use App\Networks\NetworkFactory;

class FallbackCommand implements Command
{
    public function execute($network, $chatId, $cmd, $params = '') {
        $handler = NetworkFactory::create($network);
        return $handler->sendText($chatId, 'Unknown command.');
    }

    /**
     * Here you can add your scheduled tasks.
     * This function is called every minute
     * @return void
     */
    public function schedule() {

    }
}
