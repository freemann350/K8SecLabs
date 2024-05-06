<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;
    protected $primaryKey = 'category_id';

    protected $fillable = [
        'name',
        'training_type'
    ];

    public function definitons(): HasMany
    {
        return $this->hasMany(Definition::class, 'category_id', 'category_id');
    }
}
