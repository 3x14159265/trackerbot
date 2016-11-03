<?php

namespace App\Networks;

class NetworkFactory {

    public static function create($network) {
        $network = strtolower($network);
        switch($network) {
            case 'slack':
                return new Slack();
            case 'telegram':
                return new Telegram();    
        }
    }
}
