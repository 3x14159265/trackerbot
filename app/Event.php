<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Chat;
use App\Networks\NetworkFactory;

class Event extends Model
{

    protected $casts = [
        'data' => 'array',
    ];

    public function app() {
        return $this->belongsTo('App\App');
    }

    public static function store($app, $data)
    {
        $event = new Event();
        $event->app_id = $app->id;
        $event->type = $data['type'];
        $event->event = $data['event'];
        $event->data = array_key_exists('data', $data) ? $data['data']: null;
        $event->save();

        if(starts_with($event->type, 'rt_')) {
            $chats = Chat::findByApp($app);
            foreach($chats as $chat) {
                $network = NetworkFactory::create($app->network);
                $network->sendEvent($app, $chat, $event->event, $event->data);
            }
        }

        return $event;
    }
}
