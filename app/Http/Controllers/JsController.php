<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\JsRequest;
use App\APIProviders\Builtwith;
use App\Event;
use App\App;

class JsController extends Controller
{
    public function options(Request $request, $api_key)
    {
        $app = App::findByKey($api_key);

        $response = response('');
        foreach ($app->urls as $url) {
            $response->header('Access-Control-Allow-Origin', $url);
        }
        $response->header('Access-Control-Allow-Credentials', 'true')
            ->header('Access-Control-Allow-Methods', 'POST')
            ->header('Access-Control-Allow-Headers', 'Content-Type, X-Requested-With, Accept, Authorization')
            ->header('Access-Control-Max-Age', '3600')
            ->header('Content-Type', 'application/json; charset=utf-8');

        return $response;
    }

    public function track(JsRequest $request)
    {
        $app = App::findByKey($request->header('X-Api-Key'));
        $data = $request->input('data');
        Event::store($app, $data);

        $response = response()->json(['status' => 'event_created'], 201);
        foreach ($app->urls as $url) {
            $response->header('Access-Control-Allow-Origin', $url);
        }
        $response->header('Access-Control-Allow-Credentials', 'true')
            ->header('Access-Control-Allow-Origin', 'http://tracker.app')
            ->header('Access-Control-Allow-Credentials', 'true')
            ->header('Access-Control-Allow-Methods', 'POST')
            ->header('Access-Control-Allow-Headers', 'Content-Type, X-Requested-With, Accept, Authorization')
            ->header('Access-Control-Max-Age', '3600');

        return $response;
    }

    public function domain(JsRequest $request)
    {
        $app = App::findByKey($request->header('X-Api-Key'));
        $data = $request->input('data');
        $builtwith = new Builtwith();
        $builtwith->sendInfo($app, $data['domain'], $data['filter']);

        $response = response()->json(['status' => 'event_created'], 201);
        foreach ($app->urls as $url) {
            $response->header('Access-Control-Allow-Origin', $url);
        }
        $response->header('Access-Control-Allow-Credentials', 'true')
            ->header('Access-Control-Allow-Origin', 'http://tracker.app')
            ->header('Access-Control-Allow-Credentials', 'true')
            ->header('Access-Control-Allow-Methods', 'POST')
            ->header('Access-Control-Allow-Headers', 'Content-Type, X-Requested-With, Accept, Authorization')
            ->header('Access-Control-Max-Age', '3600');

        return $response;
    }
}
