<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class App extends Model
{

    protected $casts = [
        'urls' => 'array',
    ];

    public function chats()
    {
        return $this->hasMany('App\Chat');
    }

    public function events()
    {
        return $this->hasMany('App\Event');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public static function findByKey($key)
    {
        return self::where('api_key', '=', $key)
            ->firstOrFail();
    }

    public static function findByKeyAndSecret($key, $secret)
    {
        return self::where('api_key', '=', $key)
            ->where('api_secret', '=', $secret)
            ->firstOrFail();
    }

    public static function createKey()
    {
        $keyspace = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $str = '';
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < 12; ++$i) {
            $str .= $keyspace[random_int(0, $max)];
        }
        if (self::where('api_key', '=', $str)->exists()) {
            return self::createKey();
        }

        return $str;
    }

    public static function createSecret()
    {
        $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $str = '';
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < 45; ++$i) {
            $str .= $keyspace[random_int(0, $max)];
        }

        return $str;
    }
}
