<?php

use App\Models\Setting;

if (! function_exists('site')) {
    /** Read a site setting (falls back to $default). */
    function site(string $key, mixed $default = null): mixed
    {
        try {
            return Setting::get($key, $default);
        } catch (Throwable) {
            return $default;
        }
    }
}

if (! function_exists('initials')) {
    function initials(?string $name): string
    {
        return collect(explode(' ', trim((string) $name)))
            ->filter()->take(2)->map(fn ($p) => mb_strtoupper(mb_substr($p, 0, 1)))->implode('');
    }
}

if (! function_exists('badge_color')) {
    /** Map a status keyword to a badge colour class. */
    function badge_color(?string $value): string
    {
        return match ($value) {
            'active', 'approved', 'completed', 'done', 'hired', 'replied', 'permanent', 'in_progress', 'open' => 'green',
            'pending', 'new', 'planning', 'todo', 'lead', 'medium', 'review', 'shortlisted' => 'yellow',
            'rejected', 'urgent', 'inactive', 'on_hold', 'high', 'closed' => 'red',
            'remote_part_time', 'interview', 'read', 'low', 'remote' => 'blue',
            'remote_full_time', 'contract', 'intern', 'on_leave', 'past', 'archived' => 'gray',
            default => 'gray',
        };
    }
}

if (! function_exists('money')) {
    function money(float|int|string|null $v, int $dec = 0): string
    {
        return site('currency', '$') . number_format((float) $v, $dec);
    }
}
