<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientJournal extends Model
{
    use HasFactory;
    protected $fillable = [
        'client',
        'start_year',
        'start_month',
        'end_year',
        'end_month',
    ];
}
