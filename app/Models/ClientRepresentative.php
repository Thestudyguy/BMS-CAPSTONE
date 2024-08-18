<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientRepresentative extends Model
{
    use HasFactory;
    protected $fillable = [
        'RepresentativeName',
        'RepresentativeContactInformation',
        'RepresentativeDateOfBirth',
        'RepresentativePosition',
        'RepresentativeAddress',
    ];
    
}
