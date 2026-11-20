<?php

namespace App\Http\Controllers\Admin;

use App\Models\Department;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class TeamMemberController extends ResourceController
{
    protected function model(): string
    {
        return TeamMember::class;
    }

    protected function meta(): array
    {
        return [
            'title' => 'Team', 'singular' => 'Team member', 'route' => 'admin.team', 'icon' => 'users',
            'subtitle' => 'Permanent staff and remote part-time talent — everyone who builds with us.',
            'can_create' => true,
        ];
    }

    protected function with(): array
    {
        return ['department'];
    }

    protected function searchable(): array
    {
        return ['name', 'designation', 'email', 'department.name'];
    }

    protected function filters(): array
    {
        return [
            ['name' => 'employment_type', 'label' => 'All types', 'options' => TeamMember::TYPES],
            ['name' => 'status', 'label' => 'All statuses', 'options' => ['active' => 'Active', 'on_leave' => 'On leave', 'inactive' => 'Inactive']],
        ];
    }

    protected function order($q)
    {
        return $q->orderBy('sort_order')->orderBy('id');
    }

    protected function columns(): array
    {
        return [
            ['key' => 'name', 'label' => 'Member', 'type' => 'person', 'sub' => 'designation', 'photo' => 'photo'],
            ['key' => 'department.name', 'label' => 'Department', 'type' => 'text'],
            ['key' => 'employment_type', 'label' => 'Type', 'type' => 'badge', 'map' => TeamMember::TYPES],
            ['key' => 'pay', 'label' => 'Pay', 'type' => 'render', 'render' => function (TeamMember $m) {
                if ($m->hourly_rate && str_contains($m->employment_type, 'part')) {
                    return e(money($m->hourly_rate, 2) . '/hr') . ' <small class="muted">· ' . (int) $m->weekly_hours . 'h/wk</small>';
                }

                return $m->monthly_salary ? e(money($m->monthly_salary) . '/mo') : '<span class="muted">—</span>';
            }],
            ['key' => 'status', 'label' => 'Status', 'type' => 'badge', 'map' => ['active' => 'Active', 'on_leave' => 'On leave', 'inactive' => 'Inactive']],
            ['key' => 'is_public', 'label' => 'On website', 'type' => 'bool'],
        ];
    }

    protected function fields(?Model $record = null): array
    {
        return [
            ['name' => 'name', 'label' => 'Full name', 'type' => 'text', 'rules' => 'required|string|max:120', 'col' => 6],
            ['name' => 'designation', 'label' => 'Designation / job title', 'type' => 'text', 'rules' => 'required|string|max:120', 'col' => 6],
            ['name' => 'department_id', 'label' => 'Department', 'type' => 'select', 'col' => 4,
                'options' => Department::orderBy('name')->pluck('name', 'id')->all(), 'rules' => 'nullable|exists:departments,id'],
            ['name' => 'employment_type', 'label' => 'Employment type', 'type' => 'select', 'required' => true, 'col' => 4,
                'options' => TeamMember::TYPES, 'help' => 'Permanent staff are paid monthly; remote part-timers are paid by approved hours.'],
            ['name' => 'work_mode', 'label' => 'Work mode', 'type' => 'select', 'required' => true, 'col' => 4,
                'options' => ['onsite' => 'On-site', 'hybrid' => 'Hybrid', 'remote' => 'Remote']],
            ['name' => 'monthly_salary', 'label' => 'Monthly salary (' . site('currency', '$') . ')', 'type' => 'number', 'step' => '0.01', 'rules' => 'nullable|numeric|min:0', 'col' => 4],
            ['name' => 'hourly_rate', 'label' => 'Hourly rate (' . site('currency', '$') . ')', 'type' => 'number', 'step' => '0.01', 'rules' => 'nullable|numeric|min:0', 'col' => 4],
            ['name' => 'weekly_hours', 'label' => 'Contracted hours / week', 'type' => 'number', 'rules' => 'required|integer|min:1|max:80', 'default' => 40, 'col' => 4],
            ['name' => 'email', 'label' => 'Email', 'type' => 'email', 'rules' => 'nullable|email|max:150', 'col' => 4],
            ['name' => 'phone', 'label' => 'Phone', 'type' => 'text', 'rules' => 'nullable|string|max:40', 'col' => 4],
            ['name' => 'join_date', 'label' => 'Join date', 'type' => 'date', 'rules' => 'nullable|date', 'col' => 4],
            ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'required' => true, 'col' => 4,
                'options' => ['active' => 'Active', 'on_leave' => 'On leave', 'inactive' => 'Inactive']],
            ['name' => 'user_id', 'label' => 'Linked admin login', 'type' => 'select', 'col' => 4,
                'options' => User::orderBy('name')->pluck('name', 'id')->all(), 'rules' => 'nullable|exists:users,id',
                'help' => 'Optional — lets this person see “My tasks”.'],
            ['name' => 'sort_order', 'label' => 'Display order', 'type' => 'number', 'rules' => 'nullable|integer|min:0', 'default' => 0, 'col' => 4],
            ['name' => 'skills', 'label' => 'Skills', 'type' => 'tags', 'col' => 12, 'help' => 'Comma-separated, e.g. Laravel, Flutter, Meta Ads'],
            ['name' => 'bio', 'label' => 'Short bio', 'type' => 'textarea', 'rules' => 'nullable|string|max:1000', 'col' => 12],
            ['name' => 'photo', 'label' => 'Photo', 'type' => 'image', 'folder' => 'team', 'rules' => 'nullable|image|max:4096', 'col' => 6],
            ['name' => 'is_public', 'label' => 'Show on public website', 'type' => 'checkbox', 'default' => true, 'col' => 6],
            ['name' => 'linkedin', 'label' => 'LinkedIn URL', 'type' => 'url', 'rules' => 'nullable|url|max:255', 'col' => 4],
            ['name' => 'github', 'label' => 'GitHub URL', 'type' => 'url', 'rules' => 'nullable|url|max:255', 'col' => 4],
            ['name' => 'facebook', 'label' => 'Facebook URL', 'type' => 'url', 'rules' => 'nullable|url|max:255', 'col' => 4],
        ];
    }

    protected function prepare(array $data, ?Model $record, Request $request): array
    {
        $data['slug'] = $record && $record->name === $data['name'] ? $record->slug : $this->uniqueSlug($data['name'], $record);
        $data['weekly_hours'] = $data['weekly_hours'] ?? 40;
        $data['sort_order'] = $data['sort_order'] ?? 0;

        return $data;
    }

    protected function guardDelete(Model $record): ?string
    {
        return null;
    }
}
