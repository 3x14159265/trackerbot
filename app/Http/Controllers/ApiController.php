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
    /**
     * Tracks an event
     * Input: [
     * 		'data' => [
     * 			'type' => 'event|rt_event|error|rt_error',
     * 			'event' => 'Name of the event',
     * 			'data' => [<generic array>]
     * 		]
     * ]
     */
    public function track(ApiRequest $request) {
        $app = App::findByKeyAndSecret($request->header('X-Api-Key'), $request->header('X-Api-Secret'));
        $data = $request->input('data');
        Event::store($app, $data);

        return response()->json(['status' => 'event_created'], 201);
    }

    /**
     * Tracks an event
     * Input: [
     * 		'data' => [
     * 			'domain' => 'a valid domain without https?://',
     * 			'filter' => [<optional (but recommended) array of elements to filter technology>]
     * 		]
     * ]
     */
    public function domain(ApiRequest $request) {
        $app = App::findByKeyAndSecret($request->header('X-Api-Key'), $request->header('X-Api-Secret'));
        $payload = $request->input('data');
        $meta = array_key_exists('meta', $payload) ? $payload['meta'] : [];
        $builtwith = new Builtwith();
        $builtwith->sendInfo($app, $payload['domain'], $payload['filter'], $meta);

        return response()->json(['status' => 'event_created'], 201);
    }




}
