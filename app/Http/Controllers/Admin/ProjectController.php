<?php

namespace App\Http\Controllers\Admin;

use App\Models\Client;
use App\Models\Project;
use App\Models\Service;
use Illuminate\Database\Eloquent\Model;

class ProjectController extends ResourceController
{
    public const STATUS = ['planning' => 'Planning', 'in_progress' => 'In progress', 'review' => 'In review', 'completed' => 'Completed', 'on_hold' => 'On hold'];

    protected function model(): string
    {
        return Project::class;
    }

    protected function meta(): array
    {
        return ['title' => 'Projects', 'singular' => 'Project', 'route' => 'admin.projects', 'icon' => 'folder',
            'subtitle' => 'Everything we are building for clients right now.', 'can_create' => true];
    }

    protected function with(): array
    {
        return ['client', 'service'];
    }

    protected function searchable(): array
    {
        return ['title', 'client.company', 'client.name'];
    }

    protected function filters(): array
    {
        return [['name' => 'status', 'label' => 'All statuses', 'options' => self::STATUS]];
    }

    protected function columns(): array
    {
        return [
            ['key' => 'title', 'label' => 'Project', 'type' => 'title', 'sub' => 'service.title'],
            ['key' => 'client.company', 'label' => 'Client', 'type' => 'text'],
            ['key' => 'status', 'label' => 'Status', 'type' => 'badge', 'map' => self::STATUS],
            ['key' => 'progress', 'label' => 'Progress', 'type' => 'progress'],
            ['key' => 'budget', 'label' => 'Budget', 'type' => 'money'],
            ['key' => 'due_date', 'label' => 'Due', 'type' => 'date'],
        ];
    }

    protected function fields(?Model $record = null): array
    {
        return [
            ['name' => 'title', 'label' => 'Project title', 'type' => 'text', 'rules' => 'required|string|max:180', 'col' => 12],
            ['name' => 'client_id', 'label' => 'Client', 'type' => 'select', 'col' => 6,
                'options' => Client::orderBy('company')->get()->mapWithKeys(fn ($c) => [$c->id => $c->company ?: $c->name])->all(), 'rules' => 'nullable|exists:clients,id'],
            ['name' => 'service_id', 'label' => 'Service', 'type' => 'select', 'col' => 6,
                'options' => Service::orderBy('title')->pluck('title', 'id')->all(), 'rules' => 'nullable|exists:services,id'],
            ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'required' => true, 'options' => self::STATUS, 'col' => 4],
            ['name' => 'priority', 'label' => 'Priority', 'type' => 'select', 'required' => true, 'options' => \App\Models\Task::PRIORITIES, 'col' => 4],
            ['name' => 'budget', 'label' => 'Budget (' . site('currency', '$') . ')', 'type' => 'number', 'step' => '0.01', 'rules' => 'nullable|numeric|min:0', 'col' => 4],
            ['name' => 'start_date', 'label' => 'Start date', 'type' => 'date', 'rules' => 'nullable|date', 'col' => 4],
            ['name' => 'due_date', 'label' => 'Due date', 'type' => 'date', 'rules' => 'nullable|date|after_or_equal:start_date', 'col' => 4],
            ['name' => 'progress', 'label' => 'Progress %', 'type' => 'number', 'rules' => 'required|integer|min:0|max:100', 'default' => 0, 'col' => 4],
            ['name' => 'description', 'label' => 'Description', 'type' => 'textarea', 'rules' => 'nullable|string|max:5000', 'col' => 12],
        ];
    }
}
