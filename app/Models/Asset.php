<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    protected $fillable = ['name','book_value','distribution_lat','distribution_lng','status','user_id'];

    public function owner() {
        return $this->belongsTo(User::class, 'user_id');
    }
}

