<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Call extends Model
{
    //
    protected $fillable = [
        'user_id',
        'started_at',
        'ended_at',
        'duration_seconds',
        'cost',
        'status'
    ];
}
