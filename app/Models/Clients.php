<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clients extends Model
{
    use HasFactory;
    protected $fillable = [
        'CompanyName',
        'CompanyAddress',
        'TIN',
        'CompanyEmail',
        'CEO',
        'CEODateOfBirth',
        'CEOContactInformation',
        'dataEntryUser',
        'AccountCategory',
    ];
    
}
