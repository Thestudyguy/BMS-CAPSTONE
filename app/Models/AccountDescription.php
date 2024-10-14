<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountDescription extends Model
{
    use HasFactory;
    protected $fillable = [
        'Category',
        'Description',
        'TaxType',
        'FormType',
        'Price',
        'isVisible',
        'account',
        'Category',
        'dataUserEntry',
    ];
}
