<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'action',
        'activity',
        'description',
        'ip_address',
        'user_agent',
        'browser',
        'platform',
        'platform_version',
    ];
}
