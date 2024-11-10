<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class journal_owners_equity extends Model
{
    use HasFactory;
    protected $fillable = ['client_id', 'account', 'amount', 'journal_id'];
}
