<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillingAddedDescriptions extends Model
{
    use HasFactory;
    protected $fillable = ['client_id', 'billing_id', 'description', 'amount', 'account', 'category'];
}
