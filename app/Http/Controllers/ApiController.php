<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\ApiRequest;
use App\Http\Requests\JsRequest;
use App\Chat;
use App\Event;
use App\App;
use App\Networks\NetworkFactory;

class ApiController extends Controller
{
    public function api(ApiRequest $request) {
        $data = $request->all();
        $app = App::findByKeyAndSecret($request->header('X-Api-Key'), $request->header('X-Api-Secret'));
        Event::store($app, $data);

        return response()->json(['status' => 'event_created'], 201);
    }

    public function track(JsRequest $request) {
        $app = App::findByKey($request->header('X-Api-Key'));
        $data = $request->all();
        unset($data['api_key']);
        Event::store($app, $data);

        return response()->json(['status' => 'event_created'], 201)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Credentials', 'true')
            ->header('Access-Control-Allow-Methods', 'POST')
            ->header('Access-Control-Allow-Headers', 'Content-Type, X-Requested-With, Accept, Authorization')
            ->header('Access-Control-Max-Age', '3600');
    }


}
