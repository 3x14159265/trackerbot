<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class TelegramController extends Controller
{
    public function webhook(Request $request) {
        $data = $request->all();

        \Log::debug($data);

        if(array_key_exists('message', $data)
            && array_key_exists('text', $data['message'])
            && ($data['message']['text'] == '/chat' || $data['message']['text'] == '/chat@'.getenv('TELEGRAM_BOT'))) {
            $chatId = $data['message']['chat']['id'];

            $token = getenv('TELEGRAM_TOKEN');
            $text = "Your chat ID is: *$chatId*";
            file_get_contents("https://api.telegram.org/bot$token/sendMessage?chat_id=$chatId&text=$text&parse_mode=Markdown");
        }
        return response()->json('');
    }
}
