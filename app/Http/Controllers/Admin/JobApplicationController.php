<?php

namespace App\Http\Controllers\Admin;

use App\Models\JobApplication;
use App\Models\JobOpening;
use Illuminate\Database\Eloquent\Model;

class JobApplicationController extends ResourceController
{
    protected const STATUS = ['new' => 'New', 'shortlisted' => 'Shortlisted', 'interview' => 'Interview', 'hired' => 'Hired', 'rejected' => 'Rejected'];

    protected function model(): string
    {
        return JobApplication::class;
    }

    protected function meta(): array
    {
        return ['title' => 'Applications', 'singular' => 'Application', 'route' => 'admin.applications', 'icon' => 'users',
            'subtitle' => 'People who applied through the Careers page.', 'can_create' => false];
    }

    protected function with(): array
    {
        return ['opening'];
    }

    protected function searchable(): array
    {
        return ['name', 'email', 'opening.title'];
    }

    protected function filters(): array
    {
        return [['name' => 'status', 'label' => 'All statuses', 'options' => self::STATUS]];
    }

    protected function columns(): array
    {
        return [
            ['key' => 'name', 'label' => 'Applicant', 'type' => 'person', 'sub' => 'email'],
            ['key' => 'opening.title', 'label' => 'Applied for', 'type' => 'text'],
            ['key' => 'portfolio_url', 'label' => 'Portfolio', 'type' => 'render', 'render' => fn (JobApplication $a) => $a->portfolio_url ? '<a href="' . e($a->portfolio_url) . '" target="_blank" rel="noopener">Open ↗</a>' : '—'],
            ['key' => 'created_at', 'label' => 'Received', 'type' => 'date'],
            ['key' => 'status', 'label' => 'Status', 'type' => 'badge', 'map' => self::STATUS],
        ];
    }

    protected function fields(?Model $record = null): array
    {
        return [
            ['name' => 'name', 'label' => 'Name', 'type' => 'text', 'readonly' => true, 'col' => 6],
            ['name' => 'email', 'label' => 'Email', 'type' => 'text', 'readonly' => true, 'col' => 6],
            ['name' => 'phone', 'label' => 'Phone', 'type' => 'text', 'readonly' => true, 'col' => 6],
            ['name' => 'portfolio_url', 'label' => 'Portfolio / CV link', 'type' => 'text', 'readonly' => true, 'col' => 6],
            ['name' => 'cover_letter', 'label' => 'Cover letter', 'type' => 'textarea', 'readonly' => true, 'col' => 12],
            ['name' => 'status', 'label' => 'Pipeline status', 'type' => 'select', 'required' => true, 'options' => self::STATUS, 'col' => 6],
        ];
    }

    public function create()
    {
        abort(404);
    }

    public function store(\Illuminate\Http\Request $request): \Illuminate\Http\RedirectResponse
    {
        abort(404);
    }
}
