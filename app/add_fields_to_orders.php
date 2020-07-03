<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class add_fields_to_orders extends Model
{
    protected $fillable = [
        'peritemcost','peritemretail', 
    ];
}
