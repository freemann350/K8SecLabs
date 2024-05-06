<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Definition extends Model
{
    use HasFactory;
    protected $primaryKey = 'definition_id';

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
    
    public function user_definition(): HasMany
    {
        return $this->hasMany(User_Definition::class, 'definition_id', 'definition_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
    
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }
}
