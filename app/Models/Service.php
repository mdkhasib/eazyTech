<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $guarded = [];

    public const ICONS = [
        'code', 'cart', 'phone', 'grid', 'calculator', 'users', 'store', 'pill',
        'cpu', 'trending', 'megaphone', 'share', 'video', 'palette', 'rocket', 'globe', 'layers', 'target',
    ];

    protected function casts(): array
    {
        return ['features' => 'array', 'is_featured' => 'boolean', 'is_active' => 'boolean'];
    }

    public function scopeActive($q)
    {
        return $q->where('is_active', true)->orderBy('sort_order')->orderBy('id');
    }
}
