<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Bot\CommandFactory;
use App\Chat;
use App\App;

class TelegramController extends Controller
{
    public function webhook(Request $request) {
        $data = $request->all();

        if(array_key_exists('message', $data)
            && array_key_exists('text', $data['message'])) {
                $text = $data['message']['text'];
                if(starts_with($text, '/')) $text = str_replace('@'.getenv('TELEGRAM_BOT'), '', $text);
                $params = explode(' ', $text, 2);
                if(count($params) == 1) $params[1] = '';
                $command = CommandFactory::get($params[0]);
                $result = $command->execute('telegram', $data['message']['chat']['id'], $params[0], $params[1]);
        }
        return response()->json('');
    }

    public function connect(Requests\TelegramConnectRequest $request) {
        $user = $request->user();
        $appId = $request->input('app_id');
        $app = App::findOrFail($appId);
        $chatId = $request->input('chat_id');
        $token = getenv('TELEGRAM_TOKEN');

        $telegramChat = json_decode(file_get_contents("https://api.telegram.org/bot$token/getChat?chat_id=$chatId"), true)['result'];

        $name = '';
        if(array_key_exists('title', $telegramChat)) {
            $name = $telegramChat['title'];
        } else if(array_key_exists('username', $telegramChat)) {
            $name = $telegramChat['username'];
        } else if(array_key_exists('last_name', $telegramChat)) {
            $name = $telegramChat['last_name'];
        } else if(array_key_exists('first_name', $telegramChat)) {
            $name = $telegramChat['first_name'];
        }

        if(Chat::existsByNameAndIdentifierAndApp($name, $chatId, $app)) {
            return response()->json([]);
        }

        $chat = new Chat();
        $chat->app_id = $app->id;
        $chat->platform = 'telegram';
        $chat->name = $name;
        $chat->identifier = $chatId;
        $chat->data = $telegramChat;
        $chat->save();

        return response()->json([$chat]);
    }


}
