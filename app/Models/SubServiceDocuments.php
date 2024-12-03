<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubServiceDocuments extends Model
{
    use HasFactory;
    protected $fillable = [
        'service_id',
        'ReqName',
        'getClientOriginalName',
        'getClientMimeType',
        'getSize',
        'getRealPath',
        'dataEntryUser',
        'isVisible',
        'client_service',
        'client_id',
        
    ];
}
