<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ping extends Model
{
    use HasFactory;

    protected $fillable = ['data'];
}
