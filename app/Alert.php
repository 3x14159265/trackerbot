<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{

    protected $dates = ['created_at', 'updated_at', 'fire_at'];

    public static function findUpcoming()
    {
        $now = new \DateTime();
        $now->setTime($now->format("H"), $now->format("i"), 0);
        \Log::debug($now->format('H:i:s'));
        return self::where('fire_at', '=', $now)
            ->get();
    }

    public static function findByAppId($appId)
    {
        $now = new \DateTime();
        return self::where('app_id', '=', $appId)
            ->where('fire_at', '>', $now)
            ->get();
    }

    public static function findByIdAndAppId($id, $appId)
    {
        $now = new \DateTime();
        return self::where('id', '=', $id)
            ->where('app_id', '=', $appId)
            ->first();
    }

}
