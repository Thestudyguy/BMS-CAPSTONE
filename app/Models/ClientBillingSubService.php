<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientBillingSubService extends Model
{
    use HasFactory;
    protected $fillable = ['client_id', 'billing_id', 'sub_service', 'service', 'amount', 'account'];
}
