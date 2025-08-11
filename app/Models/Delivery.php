<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    protected $fillable = ['location', 'distance_km', 'fuel_used', 'time_minutes'];
}
