<?php

namespace App\Networks;

interface Network {

    function getPrefix();

    function getInfo($chatId);

    function sendEvent($app, $chat, $event, $data);

    function sendText($chatId, $text);

}
