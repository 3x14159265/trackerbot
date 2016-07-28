<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class App extends Model
{
    protected $casts = [
        'data' => 'array',
    ];

    public function apps() {
        return $this->hasMany('App\Event');
    }

    public function user() {
        return $this->belongsTo('App\App');
    }

    public static function findByKeyAndSecret($key, $secret) {
        return self::where('api_key', '=', $key)
            ->where('api_secret', '=', $secret)
            ->firstOrFail();
    }
}
