<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Watcher extends Model
{
    protected $fillable = ['app_id', 'url'];

    public static function findByAppIdAndUrl($appId, $url) {
        return self::where('app_id', '=', $appId)
            ->where('url', '=', $url)
            ->firstOrFail();
    }
}
