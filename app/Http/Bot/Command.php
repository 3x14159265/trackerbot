<?php

namespace App\Http\Bot;

interface Command
{
    public function execute($network, $chatId, $cmd, $params = '');

    public function schedule();
}
