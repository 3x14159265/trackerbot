<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\ApiRequest;
use App\Chat;
use App\Event;
use App\App;
use App\Networks\NetworkFactory;
use App\APIProviders\Builtwith;

class ApiController extends Controller
{

    public function track(ApiRequest $request) {
        $app = App::findByKeyAndSecret($request->header('X-Api-Key'), $request->header('X-Api-Secret'));
        $data = $request->input('data');
        Event::store($app, $data);

        return response()->json(['status' => 'event_created'], 201);
    }

    public function domain(ApiRequest $request) {
        $app = App::findByKeyAndSecret($request->header('X-Api-Key'), $request->header('X-Api-Secret'));
        $data = $request->input('data');
        $builtwith = new Builtwith();
        $builtwith->sendInfo($app, $data['domain'], $data['filter']);

        return response()->json(['status' => 'event_created'], 201);
    }




}
