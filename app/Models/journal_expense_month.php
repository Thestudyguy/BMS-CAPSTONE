<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class journal_expense_month extends Model
{
    use HasFactory;
    protected $fillable = ['income_id', 'month', 'amount'];
}
