<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class UserController extends ResourceController
{
    protected function model(): string
    {
        return User::class;
    }

    protected function meta(): array
    {
        return ['title' => 'Users', 'singular' => 'User', 'route' => 'admin.users', 'icon' => 'shield',
            'subtitle' => 'Admin-panel logins. Each user gets a role that controls what they can open.', 'can_create' => true];
    }

    protected function with(): array
    {
        return ['role'];
    }

    protected function searchable(): array
    {
        return ['name', 'email', 'role.name'];
    }

    protected function order($q)
    {
        return $q->orderBy('id');
    }

    protected function columns(): array
    {
        return [
            ['key' => 'name', 'label' => 'User', 'type' => 'person', 'sub' => 'email', 'photo' => 'avatar'],
            ['key' => 'role.name', 'label' => 'Role', 'type' => 'render', 'render' => fn (User $u) => '<span class="badge violet">' . e($u->role?->name ?? 'No role') . '</span>'],
            ['key' => 'last_login_at', 'label' => 'Last login', 'type' => 'render', 'render' => fn (User $u) => $u->last_login_at ? e($u->last_login_at->diffForHumans()) : '<span class="muted">never</span>'],
            ['key' => 'is_active', 'label' => 'Active', 'type' => 'bool'],
        ];
    }

    protected function fields(?Model $record = null): array
    {
        return [
            ['name' => 'name', 'label' => 'Full name', 'type' => 'text', 'rules' => 'required|string|max:120', 'col' => 6],
            ['name' => 'email', 'label' => 'Login email', 'type' => 'email', 'col' => 6,
                'rules' => ['required', 'email', 'max:150', Rule::unique('users', 'email')->ignore($record?->id)]],
            ['name' => 'role_id', 'label' => 'Role', 'type' => 'select', 'required' => true, 'col' => 4,
                'options' => Role::orderBy('name')->pluck('name', 'id')->all(), 'rules' => 'required|exists:roles,id'],
            ['name' => 'phone', 'label' => 'Phone', 'type' => 'text', 'rules' => 'nullable|string|max:40', 'col' => 4],
            ['name' => 'is_active', 'label' => 'Account active', 'type' => 'checkbox', 'default' => true, 'col' => 4],
            ['name' => 'password', 'label' => $record ? 'New password (leave blank to keep)' : 'Password', 'type' => 'password', 'col' => 6,
                'rules' => [$record ? 'nullable' : 'required', 'string', 'min:8', 'max:100']],
            ['name' => 'avatar', 'label' => 'Avatar', 'type' => 'image', 'folder' => 'avatars', 'rules' => 'nullable|image|max:2048', 'col' => 6],
        ];
    }

    protected function guardDelete(Model $record): ?string
    {
        if ($record->id === auth()->id()) {
            return 'You cannot delete your own account.';
        }
        if ($record->role?->slug === 'super-admin' && User::whereHas('role', fn ($q) => $q->where('slug', 'super-admin'))->count() <= 1) {
            return 'This is the last Super Admin — it cannot be deleted.';
        }

        return null;
    }
}
