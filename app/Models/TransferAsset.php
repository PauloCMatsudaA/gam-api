<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransferAsset extends Model
{
    protected $fillable = ['transfer_id','asset_id','from_user_id','to_user_id','book_value_snapshot'];
}

