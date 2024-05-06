<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User_Definition extends Model
{
    use HasFactory;
    protected $primaryKey = 'user_definition_id';

    protected $fillable = [
        'user_id',
        'definition_id'
    ];

    public function environment(): HasMany
    {
        return $this->hasMany(Environment::class, 'user_definition_id', 'user_definition_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function definition(): BelongsTo
    {
        return $this->belongsTo(Definition::class, 'definition_id', 'definition_id');
    }
}
