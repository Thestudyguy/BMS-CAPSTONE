<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientServices extends Model
{
    use HasFactory;
    protected $fillable = [
        'Client',                
        'ClientService',
        'ClientServiceProgress',
        'getClientOriginalName',
        'getClientMimeType',
        'getSize',
        'getRealPath',
        'dataEntryUser',
        'isVisible',
    ];
}
