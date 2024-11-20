<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientJournal extends Model
{
    use HasFactory;
    protected $fillable = [
        'client_id',
        'year_ended',
        'journal_id',
        'dataUserEntry'
    ];
}
