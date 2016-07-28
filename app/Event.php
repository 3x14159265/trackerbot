<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{

    protected $casts = [
        'data' => 'array',
    ];

    public function app() {
        return $this->belongsTo('App\App');
    }
}
