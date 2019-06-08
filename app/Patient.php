<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    
    protected $fillable = [
         'name','id_number','tel','dob','location','department','illness','medication','payment'
    ];
}