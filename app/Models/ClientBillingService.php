<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientBillingService extends Model
{
    use HasFactory;
    protected $fillable = ['client_id', 'billing_id', 'service', 'amount', 'account'];
    
    
}
