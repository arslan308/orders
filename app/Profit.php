<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profit extends Model
{
    protected $fillable = [
        'client_id', 'month', 'profit', 'items', 'cost', 
    ];
}
