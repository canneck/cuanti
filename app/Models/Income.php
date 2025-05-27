<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    protected $fillable = [
        'datetime',
        'entity_id',
        'reason',
        'amount'
    ];
}
