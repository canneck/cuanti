<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'datetime',
        'entity_id',
        'subject_id',
        'description',
        'amount'
    ];
}
