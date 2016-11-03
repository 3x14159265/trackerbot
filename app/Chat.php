<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $casts = [
        'data' => 'array',
    ];

    protected $fillable = ['app_id', 'identifier'];

    public function app()
    {
        return $this->belongsTo('App\App');
    }

    public static function findByApp($app)
    {
        return self::where('app_id', '=', $app->id)
            ->get();
    }

    public static function findByNetworkAndIdentifier($network, $identifier)
    {
        return self::join('apps', 'apps.id', '=', 'chats.app_id')
            ->where('apps.network', '=', $network)
            ->where('chats.identifier', '=', $identifier)
            ->firstOrFail();
    }

    public static function existsByNameAndIdentifierAndApp($name, $identifier, $app)
    {
        return self::where('app_id', '=', $app->id)
            ->where('name', '=', $name)
            ->where('identifier', '=', $identifier)
            ->exists();
    }
}
