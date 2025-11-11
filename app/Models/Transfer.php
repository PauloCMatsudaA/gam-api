<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    protected $fillable = ['user_a_id','user_b_id','initiated_by_user_id','total_value_a','total_value_b'];

    public function items() {
        return $this->hasMany(TransferAsset::class);
    }
}

