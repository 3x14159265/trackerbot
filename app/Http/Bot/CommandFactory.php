<?php

namespace App\Http\Bot;
use App\Http\Bot\APIKeysCommand;
use App\Http\Bot\AlertCommand;
use App\Http\Bot\WatcherCommand;
use App\Http\Bot\FallbackCommand;
use App\Http\Bot\HelpCommand;

class CommandFactory
{

    public static $commands = [
        '/help' => HelpCommand::class,
        // '/chat' => ChatIDCommand::class,
        '/api_key' => APIKeyCommand::class,
        '/watcher' => WatcherCommand::class,
        '/alert' => AlertCommand::class,
    ];


    public static function get($command) {
        $command = strtolower(trim($command));
        if(array_key_exists($command, self::$commands)) {
            return new self::$commands[$command];
        }
        return new FallbackCommand();
    }
}
