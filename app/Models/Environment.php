<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Environment extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_definition_id',
        'access_code',
        'quantity',
        'end_date',
        'description'
    ];
    
    public function userDefinition(): BelongsTo
    {
        return $this->belongsTo(UserDefinition::class, 'user_definition_id', 'id');
    }

    public function environmentAccesses(): HasMany
    {
        return $this->hasMany(EnvironmentAccess::class, 'environment_id', 'id');
    }
}
