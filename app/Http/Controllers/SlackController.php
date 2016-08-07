<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Settings;
use App\Services\Http;
use App\Chat;
use App\App;

class SlackController extends Controller
{

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
        $app_id = $request->get('state');
        $app = App::findOrFail($app_id);

        $url = "$url?client_id=$client_id&client_secret=$client_secret&code=$code&redirect_uri=$redirect&state=";
        $result = json_decode(file_get_contents($url), true);
        if(!array_key_exists('access_token', $result)) {
            return redirect('/dashboard#/integrations/'.$app->id.'?result=error');
        }

        $name = $result['team_name'];
        $identifier = $result['incoming_webhook']['channel'];
        if(Chat::existsByNameAndIdentifierAndApp($name, $identifier, $app)) {
            return redirect('/dashboard#/integrations/'.$app->id);
        }

        $chat = new Chat();
        $chat->app_id = $app->id;
        $chat->platform = 'slack';
        $chat->name = $name;
        $chat->identifier = $identifier;
        $chat->data = $result;
        $chat->save();

        return redirect('/dashboard#/integrations/'.$app->id);
    }


}
