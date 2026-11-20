<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PortfolioItem extends Model
{
    protected $guarded = [];

    public const CATEGORIES = [
        'web'       => 'Web App',
        'ecommerce' => 'E-commerce',
        'mobile'    => 'Mobile App',
        'erp'       => 'ERP & Business',
        'marketing' => 'Marketing',
        'ai'        => 'AI & Video',
    ];

    public const THEMES = ['teal', 'orange', 'coral', 'yellow', 'violet', 'mint'];

    protected function casts(): array
    {
        return ['tags' => 'array', 'is_featured' => 'boolean', 'is_published' => 'boolean'];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function scopePublished($q)
    {
        return $q->where('is_published', true)->orderBy('sort_order')->orderByDesc('id');
    }

    public function getCoverUrlAttribute(): ?string
    {
        return $this->cover ? asset($this->cover) : null;
    }

    public function getCategoryLabelAttribute(): string
    {
        return self::CATEGORIES[$this->category] ?? ucfirst($this->category);
    }
}
