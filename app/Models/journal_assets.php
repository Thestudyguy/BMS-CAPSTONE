<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class journal_assets extends Model
{
    use HasFactory;
    protected $fillable = ['client_id', 'asset_category', 'account', 'amount', 'journal_id'];
}
