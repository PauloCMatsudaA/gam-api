<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LocationHistory extends Model
{
    protected $fillable = ['user_id','lat','lng','recorded_at'];

    public function user() {
        return $this->belongsTo(User::class);
    }
}

