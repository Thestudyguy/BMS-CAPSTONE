<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientBilling extends Model
{
    use HasFactory;

    protected $fillable = [
        'billing_id',
        'client_id',
        'client_parent_services_id',
        'client_sub_services_id',
        'added_description_id',
        'due_date',
    ];
}
