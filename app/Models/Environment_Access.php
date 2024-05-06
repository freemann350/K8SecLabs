<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Environment_Access extends Model
{
    use HasFactory;
    protected $primaryKey = 'environment_access_id';

    protected $fillable = [
        'environment_id',
        'user_id',
        'address',
        'access_port',
        'last_access'
    ];
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function environment(): BelongsTo
    {
        return $this->belongsTo(Environment::class, 'environment_id', 'environment_id');
    }
}
