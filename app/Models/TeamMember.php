<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TeamMember extends Model
{
    protected $guarded = [];

    public const TYPES = [
        'permanent'        => 'Permanent',
        'remote_part_time' => 'Remote Part-time',
        'remote_full_time' => 'Remote Full-time',
        'contract'         => 'Contract',
        'intern'           => 'Intern',
    ];

    protected function casts(): array
    {
        return [
            'skills' => 'array',
            'join_date' => 'date',
            'is_public' => 'boolean',
            'monthly_salary' => 'decimal:2',
            'hourly_rate' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    public function workLogs(): HasMany
    {
        return $this->hasMany(WorkLog::class);
    }

    public function leaves(): HasMany
    {
        return $this->hasMany(LeaveRequest::class);
    }

    public function getTypeLabelAttribute(): string
    {
        return self::TYPES[$this->employment_type] ?? ucfirst($this->employment_type);
    }

    public function getInitialsAttribute(): string
    {
        return collect(explode(' ', trim($this->name)))
            ->filter()->take(2)->map(fn ($p) => mb_strtoupper(mb_substr($p, 0, 1)))->implode('');
    }

    public function getPhotoUrlAttribute(): ?string
    {
        return $this->photo ? asset($this->photo) : null;
    }

    public function scopePublic($q)
    {
        return $q->where('is_public', true)->where('status', '!=', 'inactive')->orderBy('sort_order')->orderBy('id');
    }
}
