<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\ApiRequest;
use App\Event;
use App\App;

class ApiController extends Controller
{
    public function post(ApiRequest $request) {
        $header = $request->header('Authorization');
        $auth = base64_decode(trim(str_replace('Basic', '', $header)));
        $auth = explode(':', $auth);
        $key = $auth[0];
        $secret = $auth[1];

        $data = $request->all();

        $app = App::findByKeyAndSecret($key, $secret);

        $event = new Event();
        $event->app_id = $app->id;
        $event->type = $data['type'];
        $event->event = $data['event'];
        $event->data = array_key_exists('data', $data) ? $data['data']: null;
        $event->save();

        if($event->type == 'rt_event') {
            switch($app->platform) {
                case 'telegram':
                    $result = $this->sendTelegram($app, $event->event, $event->data);
                    break;
                case 'slack':
                    break;
                default:
                    return response()->json(['error' => 'Invalid platform'], 404);
            }
        } else {
            $result = [
                'response' => $event,
                'status' => 201
            ];
        }

        return response()->json([$result['response']], $result['status']);
    }

    private function sendTelegram($app, $event, $data) {
        $text = '📌 *Event*: '.$event.PHP_EOL.PHP_EOL;
        $text .= '📎 *Data*: '.PHP_EOL;
        foreach($data as $k=>$v) {
            $text .= "🔹 $k: $v".PHP_EOL;
        }

        $params = [
            'chat_id' => $app->data['chat_id'],
            'text' => $text,
            'parse_mode' => 'Markdown'
        ];

        $token = getenv('TELEGRAM_TOKEN');
        return $this->send("https://api.telegram.org/bot$token/sendMessage", $params);
    }

    private function send($url, $params) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        $output = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $result = [
            'status' => $status,
            'response' => json_decode($output, true)
        ];
        return $result;
    }
}
