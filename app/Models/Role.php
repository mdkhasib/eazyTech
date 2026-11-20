<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    protected $guarded = [];

    /** Every module an admin role can be granted access to. */
    public const MODULES = [
        'dashboard'    => 'Dashboard',
        'team'         => 'Team & Departments',
        'hr'           => 'HR (work logs, leave, payroll)',
        'careers'      => 'Careers & applications',
        'tasks'        => 'Task board',
        'projects'     => 'Projects',
        'clients'      => 'Clients',
        'portfolio'    => 'Portfolio',
        'services'     => 'Services',
        'testimonials' => 'Testimonials',
        'messages'     => 'Leads & messages',
        'users'        => 'Users',
        'roles'        => 'Roles & permissions',
        'settings'     => 'Site settings',
    ];

    protected function casts(): array
    {
        return ['permissions' => 'array', 'is_system' => 'boolean'];
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
