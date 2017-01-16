<?php

namespace App\Networks;

interface Network {

    function getPrefix();

    function getInfo($chatId);

    function sendEvent($app, $chat, $event);

    function sendText($chatId, $text, $meta = []);

}
