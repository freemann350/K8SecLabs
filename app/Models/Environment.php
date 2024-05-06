<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Environment extends Model
{
    use HasFactory;
    protected $primaryKey = 'environment_id';

    protected $fillable = [
        'user_definition_id',
        'access_code',
        'quantity',
        'end_date',
        'description'
    ];
    
    public function user_definition(): BelongsTo
    {
        return $this->belongsTo(User_Definition::class, 'user_definiton_id', 'user_definiton_id');
    }

    public function environment_access(): HasMany
    {
        return $this->hasMany(Environment_Access::class, 'environment_id', 'environment_id');
    }
}
