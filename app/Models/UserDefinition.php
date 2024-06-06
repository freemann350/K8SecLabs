<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserDefinition extends Model
{
    use HasFactory;
    protected $table = 'user_definitions';

    protected $fillable = [
        'user_id',
        'definition_id'
    ];

    public function environments(): HasMany
    {
        return $this->hasMany(Environment::class, 'user_definition_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function definition(): BelongsTo
    {
        return $this->belongsTo(Definition::class, 'definition_id', 'id');
    }
}
