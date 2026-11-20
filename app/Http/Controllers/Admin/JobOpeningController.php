<?php

namespace App\Http\Controllers\Admin;

use App\Models\JobOpening;
use App\Models\TeamMember;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class JobOpeningController extends ResourceController
{
    protected function model(): string
    {
        return JobOpening::class;
    }

    protected function meta(): array
    {
        return ['title' => 'Job openings', 'singular' => 'Job opening', 'route' => 'admin.jobs', 'icon' => 'briefcase',
            'subtitle' => 'Open roles shown on the public Careers page — permanent or remote part-time.', 'can_create' => true];
    }

    protected function searchable(): array
    {
        return ['title', 'department'];
    }

    protected function columns(): array
    {
        return [
            ['key' => 'title', 'label' => 'Role', 'type' => 'title', 'sub' => 'location'],
            ['key' => 'department', 'label' => 'Department', 'type' => 'text'],
            ['key' => 'employment_type', 'label' => 'Type', 'type' => 'badge', 'map' => TeamMember::TYPES],
            ['key' => 'applications', 'label' => 'Applicants', 'type' => 'render', 'render' => fn (JobOpening $j) => (string) $j->applications()->count()],
            ['key' => 'is_open', 'label' => 'Open', 'type' => 'bool'],
        ];
    }

    protected function fields(?Model $record = null): array
    {
        return [
            ['name' => 'title', 'label' => 'Job title', 'type' => 'text', 'rules' => 'required|string|max:150', 'col' => 8],
            ['name' => 'department', 'label' => 'Department', 'type' => 'text', 'rules' => 'nullable|string|max:100', 'col' => 4],
            ['name' => 'employment_type', 'label' => 'Employment type', 'type' => 'select', 'required' => true, 'options' => TeamMember::TYPES, 'col' => 4],
            ['name' => 'location', 'label' => 'Location / schedule', 'type' => 'text', 'rules' => 'required|string|max:150', 'default' => 'Remote', 'col' => 6],
            ['name' => 'is_open', 'label' => 'Accepting applications', 'type' => 'checkbox', 'default' => true, 'col' => 2],
            ['name' => 'description', 'label' => 'About the role', 'type' => 'textarea', 'rules' => 'nullable|string|max:5000', 'col' => 12],
            ['name' => 'requirements', 'label' => 'Requirements (one per line)', 'type' => 'textarea', 'rules' => 'nullable|string|max:5000', 'col' => 12],
        ];
    }

    protected function prepare(array $data, ?Model $record, Request $request): array
    {
        $data['slug'] = $record && $record->title === $data['title'] ? $record->slug : $this->uniqueSlug($data['title'], $record);

        return $data;
    }
}
