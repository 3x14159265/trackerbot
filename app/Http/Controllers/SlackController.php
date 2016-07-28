<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Settings;
use App\Services\Http;
use App\App;

class SlackController extends Controller
{

    public function __construct() {
        // $this->api = 'https://slack.com/api/';
    }

    /**
     * https://slack.com/api/oauth.access.
     *
     * client_id     - issued when you created your app (required)
     * client_secret - issued when you created your app (required)
     * code          - the code param (required)
     * redirect_uri  - must match the originally submitted URI (if one was sent)
     *
     * @return \Illuminate\Http\Response
     */
    public function auth(Request $request)
    {
        $user = $request->user();

        $code = $request->get('code');
        if (!$code) {
            return redirect('/dashboard?result=error');
        }

        $url = 'https://slack.com/api/oauth.access';
        $client_id = getenv('SLACK_KEY');
        $client_secret = getenv('SLACK_SECRET');
        $redirect = config('SLACK_REDIRECT_URL');

        $url = "$url?client_id=$client_id&client_secret=$client_secret&code=$code&redirect_uri=$redirect";
        $result = json_decode(file_get_contents($url), true);
        if(!array_key_exists('access_token', $result)) {
            return redirect('/dashboard?result=error');
        }

        $app = new App();
        $app->user_id = $user->id;
        $app->platform = 'slack';
        $app->name = $result['team_name'];
        $app->api_key = App::createKey($result['team_id']);
        $app->api_secret = App::createSecret();
        $app->data = $result;
        $app->save();

        return redirect('/dashboard');
    }

    /**
     * https://slack.com/api/oauth.access.
     *
     * client_id     - issued when you created your app (required)
     * client_secret - issued when you created your app (required)
     * code          - the code param (required)
     * redirect_uri  - must match the originally submitted URI (if one was sent)
     *
     * @return \Illuminate\Http\Response
     */
    // public function channel(Requests\SlackChannelRequest $request)
    // {
    //     $user = $request->user();
    //     $settings = $user->settings()->first();
    //     $data = $settings->data;
    //     $channel = $request->input('channel');
    //
    //     // create or join channel
    //     $url = $this->api.'channels.join';
    //     $http = new Http();
    //     $result = $http->post($url, ['token' => $data['slack']['access_token'], 'name' => $channel], []);
    //     $result = json_decode($result, true);
    //     if(!$result['ok']) {
    //         app()->abort(400, 'Can\'t join or create channel. Error: '.$result['error']);
    //     }
    //     $data['slack']['channel'] = $result['channel'];
    //
    //     // add oratio bot to channel
    //     $url = $this->api.'channels.invite';
    //     $http = new Http();
    //     $channel = $data['slack']['channel']['id'];
    //     $bot = $data['slack']['bot']['bot_user_id'];
    //     $http->post($url, ['token' => $data['slack']['access_token'], 'channel' => $channel, 'user' => $bot], []);
    //
    //     $settings->data = $data;
    //     $settings->save();
    //
    //     return response()->json($settings);
    // }
}
