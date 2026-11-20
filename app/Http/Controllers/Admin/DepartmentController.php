<?php

namespace App\Http\Controllers\Admin;

use App\Models\Department;
use Illuminate\Database\Eloquent\Model;

class DepartmentController extends ResourceController
{
    protected function model(): string
    {
        return Department::class;
    }

    protected function meta(): array
    {
        return ['title' => 'Departments', 'singular' => 'Department', 'route' => 'admin.departments', 'icon' => 'layers',
            'subtitle' => 'Group your people by function.', 'can_create' => true];
    }

    protected function order($q)
    {
        return $q->orderBy('name');
    }

    protected function columns(): array
    {
        return [
            ['key' => 'name', 'label' => 'Department', 'type' => 'render', 'render' => fn (Department $d) => '<span class="dot" style="background:' . e($d->color) . '"></span> <strong>' . e($d->name) . '</strong>'],
            ['key' => 'description', 'label' => 'Description', 'type' => 'text'],
            ['key' => 'members_count', 'label' => 'Members', 'type' => 'render', 'render' => fn (Department $d) => (string) $d->members()->count()],
        ];
    }

    protected function fields(?Model $record = null): array
    {
        return [
            ['name' => 'name', 'label' => 'Name', 'type' => 'text', 'rules' => 'required|string|max:100', 'col' => 8],
            ['name' => 'color', 'label' => 'Colour', 'type' => 'color', 'rules' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'], 'default' => '#0DB5BC', 'col' => 4],
            ['name' => 'description', 'label' => 'Description', 'type' => 'text', 'rules' => 'nullable|string|max:255', 'col' => 12],
        ];
    }

    protected function guardDelete(Model $record): ?string
    {
        return $record->members()->exists() ? 'This department still has team members — move them first.' : null;
    }
}
