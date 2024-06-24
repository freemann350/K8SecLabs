<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EnvironmentAccess extends Model
{
    use HasFactory;

    protected $table = 'environment_access';

    protected $fillable = [
        'environment_id',
        'user_id',
        'description',
        'last_access'
    ];
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->withTrashed();
    }

    public function environment(): BelongsTo
    {
        return $this->belongsTo(Environment::class, 'environment_id', 'id');
    }
}
