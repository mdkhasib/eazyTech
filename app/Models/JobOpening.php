<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobOpening extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return ['is_open' => 'boolean'];
    }

    public function applications(): HasMany
    {
        return $this->hasMany(JobApplication::class);
    }
}
