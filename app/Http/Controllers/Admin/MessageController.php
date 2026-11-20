<?php

namespace App\Http\Controllers\Admin;

use App\Models\ContactMessage;
use Illuminate\Database\Eloquent\Model;

class MessageController extends ResourceController
{
    protected const STATUS = ['new' => 'New', 'read' => 'Read', 'replied' => 'Replied', 'archived' => 'Archived'];

    protected function model(): string
    {
        return ContactMessage::class;
    }

    protected function meta(): array
    {
        return ['title' => 'Leads & messages', 'singular' => 'Message', 'route' => 'admin.messages', 'icon' => 'mail',
            'subtitle' => 'Enquiries submitted through the website contact form.', 'can_create' => false];
    }

    protected function searchable(): array
    {
        return ['name', 'email', 'service', 'message'];
    }

    protected function filters(): array
    {
        return [['name' => 'status', 'label' => 'All statuses', 'options' => self::STATUS]];
    }

    protected function columns(): array
    {
        return [
            ['key' => 'name', 'label' => 'From', 'type' => 'person', 'sub' => 'email'],
            ['key' => 'service', 'label' => 'Interested in', 'type' => 'text'],
            ['key' => 'budget', 'label' => 'Budget', 'type' => 'text'],
            ['key' => 'created_at', 'label' => 'Received', 'type' => 'date'],
            ['key' => 'status', 'label' => 'Status', 'type' => 'badge', 'map' => self::STATUS],
        ];
    }

    protected function rowActions(Model $record): array
    {
        $actions = [];
        if ($record->status === 'new') {
            $actions[] = ['label' => 'Mark read', 'tone' => 'ghost', 'url' => route('admin.messages.status', $record->id), 'data' => ['status' => 'read']];
        }
        if (in_array($record->status, ['new', 'read'], true)) {
            $actions[] = ['label' => 'Replied', 'tone' => 'ok', 'url' => route('admin.messages.status', $record->id), 'data' => ['status' => 'replied']];
        }

        return $actions;
    }

    protected function fields(?Model $record = null): array
    {
        return [
            ['name' => 'name', 'label' => 'Name', 'type' => 'text', 'readonly' => true, 'col' => 6],
            ['name' => 'email', 'label' => 'Email', 'type' => 'text', 'readonly' => true, 'col' => 6],
            ['name' => 'phone', 'label' => 'Phone', 'type' => 'text', 'readonly' => true, 'col' => 4],
            ['name' => 'service', 'label' => 'Service', 'type' => 'text', 'readonly' => true, 'col' => 4],
            ['name' => 'budget', 'label' => 'Budget', 'type' => 'text', 'readonly' => true, 'col' => 4],
            ['name' => 'message', 'label' => 'Message', 'type' => 'textarea', 'readonly' => true, 'rows' => 6, 'col' => 12],
            ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'required' => true, 'options' => self::STATUS, 'col' => 6],
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
