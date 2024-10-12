<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemProfile extends Model
{
    use HasFactory;
    protected $fillable = ['PhoneNumber', 'Email', 'Address'];
}
