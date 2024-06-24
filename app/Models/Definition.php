<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Definition extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'category_id',
        'name',
        'path',
        'description',
        'private',
        'checksum',
        'tags'
    ];
    
    public function userDefinitions(): HasMany
    {
        return $this->hasMany(UserDefinition::class, 'definition_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->withTrashed();
    }
    
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id')->withTrashed();
    }
}
