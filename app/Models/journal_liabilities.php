<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class journal_liabilities extends Model
{
    use HasFactory;
    protected $fillable = ['client_id', 'account', 'amount', 'journal_id'];
}
