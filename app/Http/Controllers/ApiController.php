<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\ApiRequest;
use App\Http\Requests\JsRequest;
use App\Chat;
use App\Event;
use App\App;

class ApiController extends Controller
{
    public function api(ApiRequest $request) {
        $header = $request->header('Authorization');
        $auth = base64_decode(trim(str_replace('Basic', '', $header)));
        $auth = explode(':', $auth);
        $key = $auth[0];
        $secret = $auth[1];

        $data = $request->all();
        $app = App::findByKeyAndSecret($key, $secret);
        $result = $this->storeEvent($app, $data);

        return response()->json('', 201);
    }

    public function track(JsRequest $request) {
        $data = $request->all();
        $app = App::findByKey($data['api_key']);
        unset($data['api_key']);

        $result = $this->storeEvent($app, $data);

        return response()->json([$result['response']], $result['status'])
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Credentials', 'true')
            ->header('Access-Control-Allow-Methods', 'POST')
            ->header('Access-Control-Allow-Headers', 'Content-Type, X-Requested-With, Accept, Authorization')
            ->header('Access-Control-Max-Age', '3600');
    }

    private function storeEvent($app, $data) {
        $event = new Event();
        $event->app_id = $app->id;
        $event->type = $data['type'];
        $event->event = $data['event'];
        $event->data = array_key_exists('data', $data) ? $data['data']: null;
        $event->save();

        if($event->type == 'rt_event') {
            $chats = Chat::findByApp($app);
            foreach($chats as $chat) {
                switch($chat->platform) {
                    case 'telegram':
                        $result = $this->sendTelegram($app, $chat, $event->event, $event->data);
                        break;
                    case 'slack':
                        $result = $this->sendSlack($app, $chat, $event->event, $event->data);
                        break;
                    default:
                        return response()->json(['error' => 'Invalid platform'], 404);
                }
            }
        } else {
            $result = [
                'response' => $event,
                'status' => 201
            ];
        }

        return $result;
    }

    private function sendTelegram($app, $chat, $event, $data) {
        $text = '🌐 *App*: '.$app->name.PHP_EOL;
        $text .= '📌 *Event*: '.$event.PHP_EOL.PHP_EOL;
        if($data) {
            $text .= '📎 *Data*: '.PHP_EOL;
            foreach($data as $k=>$v) {
                $text .= "🔹 $k: $v".PHP_EOL;
            }
        }

        $params = [
            'chat_id' => $chat->identifier,
            'text' => $text,
            'parse_mode' => 'Markdown'
        ];

        $token = getenv('TELEGRAM_TOKEN');
        return $this->send("https://api.telegram.org/bot$token/sendMessage", $params);
    }

    private function sendSlack($app, $chat, $event, $data) {
        $text = '🌐 *App*: '.$app->name.PHP_EOL;
        $text .= '📌 *Event*: '.$event;
        if($data) {
            $attachment = [
                'fallback' => $text,
                'fields' => []
            ];
            foreach($data as $k=>$v) {
                $attachment['fields'][] = ['title' => $k, 'value' => $v, 'short' => true];
            }
        }

        $params = [
            'token' => $chat->data['bot']['bot_access_token'],
            'channel' => $chat->data['incoming_webhook']['channel_id'],
            'text' => $text
        ];

        if($data) {
            $params['attachments'] = json_encode([$attachment]);
        }

        return $this->send("https://slack.com/api/chat.postMessage", $params);
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
