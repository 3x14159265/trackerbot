<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\AppRequest;
use App\App;

class DashboardController extends Controller
{
    public function index(Request $request) {

        return view('dashboard');
    }

    public function createApp(AppRequest $request) {
        $user = $request->user();

        $app = new App();
        $app->user_id = $user->id;
        $app->name = $request->input('name');
        $app->api_key = App::createKey();
        $app->api_secret = App::createSecret();
        $app->save();

        return response()->json($app);
    }

    public function apps(Request $request) {
        $user = $request->user();
        $apps = $user->apps()->get();
        return response()->json($apps);
    }

    public function integrations(Request $request, $appId) {
        $user = $request->user();
        $app = App::with('chats')->findOrFail($appId);
        return response()->json($app);
    }
}
