<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class RoleController extends ResourceController
{
    protected function model(): string
    {
        return Role::class;
    }

    protected function meta(): array
    {
        return ['title' => 'Roles & permissions', 'singular' => 'Role', 'route' => 'admin.roles', 'icon' => 'shield',
            'subtitle' => 'Decide which sections of the admin panel each role can open.', 'can_create' => true];
    }

    protected function order($q)
    {
        return $q->orderBy('id');
    }

    protected function columns(): array
    {
        return [
            ['key' => 'name', 'label' => 'Role', 'type' => 'title', 'sub' => 'description'],
            ['key' => 'permissions', 'label' => 'Access', 'type' => 'render', 'render' => function (Role $r) {
                $p = $r->permissions ?? [];
                if (in_array('*', $p, true)) {
                    return '<span class="badge orange">Full access</span>';
                }

                return collect($p)->map(fn ($m) => '<span class="chip">' . e($m) . '</span>')->implode(' ') ?: '<span class="muted">none</span>';
            }],
            ['key' => 'users', 'label' => 'Users', 'type' => 'render', 'render' => fn (Role $r) => (string) $r->users()->count()],
        ];
    }

    protected function fields(?Model $record = null): array
    {
        return [
            ['name' => 'name', 'label' => 'Role name', 'type' => 'text', 'rules' => 'required|string|max:80', 'col' => 6],
            ['name' => 'description', 'label' => 'Description', 'type' => 'text', 'rules' => 'nullable|string|max:255', 'col' => 6],
            ['name' => 'permissions', 'label' => 'Section access', 'type' => 'permissions', 'col' => 12],
        ];
    }

    protected function prepare(array $data, ?Model $record, Request $request): array
    {
        $data['slug'] = $record && $record->name === $data['name'] ? $record->slug : $this->uniqueSlug($data['name'], $record);

        return $data;
    }

    protected function guardDelete(Model $record): ?string
    {
        if ($record->is_system) {
            return 'System roles cannot be deleted.';
        }

        return $record->users()->exists() ? 'Users are still assigned to this role.' : null;
    }
}
