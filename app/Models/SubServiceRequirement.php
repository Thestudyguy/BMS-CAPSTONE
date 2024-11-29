<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubServiceRequirement extends Model
{
    use HasFactory;
    protected $fillable = ['sub_service_id', 'req_name'];
}
