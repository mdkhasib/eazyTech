<?php

namespace App\Http\Controllers\Admin;

use App\Models\Client;
use Illuminate\Database\Eloquent\Model;

class ClientController extends ResourceController
{
    protected const STATUS = ['lead' => 'Lead', 'active' => 'Active client', 'past' => 'Past client'];

    protected function model(): string
    {
        return Client::class;
    }

    protected function meta(): array
    {
        return ['title' => 'Clients', 'singular' => 'Client', 'route' => 'admin.clients', 'icon' => 'briefcase',
            'subtitle' => 'Leads, active customers and past clients in one list.', 'can_create' => true];
    }

    protected function searchable(): array
    {
        return ['name', 'company', 'email', 'country', 'industry'];
    }

    protected function filters(): array
    {
        return [['name' => 'status', 'label' => 'All statuses', 'options' => self::STATUS]];
    }

    protected function columns(): array
    {
        return [
            ['key' => 'company', 'label' => 'Client', 'type' => 'person', 'sub' => 'name', 'photo' => 'logo', 'fallback' => 'company'],
            ['key' => 'industry', 'label' => 'Industry', 'type' => 'text'],
            ['key' => 'country', 'label' => 'Country', 'type' => 'text'],
            ['key' => 'email', 'label' => 'Contact', 'type' => 'render', 'render' => fn (Client $c) => e($c->email) . ($c->phone ? '<br><small class="muted">' . e($c->phone) . '</small>' : '')],
            ['key' => 'projects', 'label' => 'Projects', 'type' => 'render', 'render' => fn (Client $c) => (string) $c->projects()->count()],
            ['key' => 'status', 'label' => 'Status', 'type' => 'badge', 'map' => self::STATUS],
        ];
    }

    protected function fields(?Model $record = null): array
    {
        return [
            ['name' => 'name', 'label' => 'Contact person', 'type' => 'text', 'rules' => 'required|string|max:120', 'col' => 6],
            ['name' => 'company', 'label' => 'Company / brand', 'type' => 'text', 'rules' => 'nullable|string|max:150', 'col' => 6],
            ['name' => 'email', 'label' => 'Email', 'type' => 'email', 'rules' => 'nullable|email|max:150', 'col' => 4],
            ['name' => 'phone', 'label' => 'Phone / WhatsApp', 'type' => 'text', 'rules' => 'nullable|string|max:40', 'col' => 4],
            ['name' => 'website', 'label' => 'Website', 'type' => 'url', 'rules' => 'nullable|url|max:255', 'col' => 4],
            ['name' => 'industry', 'label' => 'Industry', 'type' => 'text', 'rules' => 'nullable|string|max:100', 'col' => 4],
            ['name' => 'country', 'label' => 'Country', 'type' => 'text', 'rules' => 'nullable|string|max:100', 'col' => 4],
            ['name' => 'source', 'label' => 'Lead source', 'type' => 'text', 'rules' => 'nullable|string|max:100', 'col' => 4, 'help' => 'Referral, Facebook, website…'],
            ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'required' => true, 'options' => self::STATUS, 'col' => 6],
            ['name' => 'logo', 'label' => 'Logo', 'type' => 'image', 'folder' => 'clients', 'rules' => 'nullable|image|max:2048', 'col' => 6],
            ['name' => 'notes', 'label' => 'Notes', 'type' => 'textarea', 'rules' => 'nullable|string|max:3000', 'col' => 12],
        ];
    }
}
