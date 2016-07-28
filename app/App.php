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

    public static function createKey($prefix) {
        $keyspace = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $str = '';
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < 8; ++$i) {
            $str .= $keyspace[random_int(0, $max)];
        }
        return $prefix.'-'.$str;
    }

    public static function createSecret() {
        $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $str = '';
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < 45; ++$i) {
            $str .= $keyspace[random_int(0, $max)];
        }
        return $str;
    }
}
