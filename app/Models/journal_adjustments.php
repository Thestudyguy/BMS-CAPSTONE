<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class journal_adjustments extends Model
{
    use HasFactory;
    protected $fillable = ['client_id', 'owners_contribution', 'owners_withdrawal', 'journal_id', 'isAltered'];
}
