<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicesSubTable extends Model
{
    use HasFactory;
    protected $fillable = ['ServiceRequirements', 'ServiceRequirementPrice', 'BelongsToService', 'dataEntryUser', 'isVisible'];
    
}
