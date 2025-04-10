<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Marker extends Model
{
    // Kaitsta kõik väljad, välja arvatud need, mis on täidetavad
    protected $guarded = [];
    // Või, kui tahad piirata ainult soovitud väljade täitmist, kasuta fillable
    // protected $fillable = ['name', 'description', 'latitude', 'longitude'];
}
