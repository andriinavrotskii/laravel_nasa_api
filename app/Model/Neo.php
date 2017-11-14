<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Neo extends Model
{
    protected $fillable = ['reference', 'date', 'name', 'speed', 'is_hazardous']; 
}
