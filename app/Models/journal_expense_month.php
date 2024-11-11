<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class journal_expense_month extends Model
{
    use HasFactory;
    protected $fillable = ['expense_id', 'month', 'amount'];
}
