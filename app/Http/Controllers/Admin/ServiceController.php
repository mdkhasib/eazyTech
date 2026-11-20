<?php

namespace App\Http\Controllers\Admin;

use App\Models\Service;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ServiceController extends ResourceController
{
    protected function model(): string
    {
        return Service::class;
    }

    protected function meta(): array
    {
        return ['title' => 'Services', 'singular' => 'Service', 'route' => 'admin.services', 'icon' => 'rocket',
            'subtitle' => 'What we sell. Shown on the public website.', 'can_create' => true];
    }

    protected function perPage(): int
    {
        return 20;
    }

    protected function order($q)
    {
        return $q->orderBy('sort_order')->orderBy('id');
    }

    protected function searchable(): array
    {
        return ['title', 'short_description'];
    }

    protected function filters(): array
    {
        return [['name' => 'category', 'label' => 'All categories', 'options' => ['software' => 'Software', 'marketing' => 'Marketing & Social']]];
    }

    protected function columns(): array
    {
        return [
            ['key' => 'title', 'label' => 'Service', 'type' => 'title', 'sub' => 'short_description'],
            ['key' => 'category', 'label' => 'Category', 'type' => 'badge', 'map' => ['software' => 'Software', 'marketing' => 'Marketing & Social']],
            ['key' => 'is_featured', 'label' => 'Featured', 'type' => 'bool'],
            ['key' => 'is_active', 'label' => 'Visible', 'type' => 'bool'],
            ['key' => 'sort_order', 'label' => 'Order', 'type' => 'text'],
        ];
    }

    protected function fields(?Model $record = null): array
    {
        return [
            ['name' => 'title', 'label' => 'Service name', 'type' => 'text', 'rules' => 'required|string|max:150', 'col' => 8],
            ['name' => 'category', 'label' => 'Category', 'type' => 'select', 'required' => true, 'col' => 4,
                'options' => ['software' => 'Software', 'marketing' => 'Marketing & Social']],
            ['name' => 'icon', 'label' => 'Icon', 'type' => 'select', 'required' => true, 'col' => 4,
                'options' => array_combine(Service::ICONS, array_map('ucfirst', Service::ICONS))],
            ['name' => 'sort_order', 'label' => 'Display order', 'type' => 'number', 'rules' => 'nullable|integer|min:0', 'default' => 0, 'col' => 4],
            ['name' => 'is_featured', 'label' => 'Feature on home page', 'type' => 'checkbox', 'col' => 2],
            ['name' => 'is_active', 'label' => 'Visible', 'type' => 'checkbox', 'default' => true, 'col' => 2],
            ['name' => 'short_description', 'label' => 'Short description', 'type' => 'text', 'rules' => 'required|string|max:255', 'col' => 12],
            ['name' => 'description', 'label' => 'Full description', 'type' => 'textarea', 'rows' => 6, 'rules' => 'nullable|string|max:6000', 'col' => 12],
            ['name' => 'features', 'label' => 'Key features', 'type' => 'tags', 'col' => 12, 'help' => 'One per line or comma-separated.'],
        ];
    }

    protected function prepare(array $data, ?Model $record, Request $request): array
    {
        $data['slug'] = $record && $record->title === $data['title'] ? $record->slug : $this->uniqueSlug($data['title'], $record);
        $data['sort_order'] = $data['sort_order'] ?? 0;

        return $data;
    }
}
