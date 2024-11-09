<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class journal_income extends Model
{
    use HasFactory;
    protected $fillable = ['client_id', 'account', 'start_date', 'end_date', 'journal_id'];
}
