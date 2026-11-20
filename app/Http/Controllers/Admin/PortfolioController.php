<?php

namespace App\Http\Controllers\Admin;

use App\Models\Client;
use App\Models\PortfolioItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class PortfolioController extends ResourceController
{
    protected function model(): string
    {
        return PortfolioItem::class;
    }

    protected function meta(): array
    {
        return ['title' => 'Portfolio', 'singular' => 'Portfolio item', 'route' => 'admin.portfolio', 'icon' => 'layers',
            'subtitle' => 'Case studies shown on the public website.', 'can_create' => true];
    }

    protected function with(): array
    {
        return ['client'];
    }

    protected function searchable(): array
    {
        return ['title', 'summary'];
    }

    protected function filters(): array
    {
        return [['name' => 'category', 'label' => 'All categories', 'options' => PortfolioItem::CATEGORIES]];
    }

    protected function order($q)
    {
        return $q->orderBy('sort_order')->orderByDesc('id');
    }

    protected function columns(): array
    {
        return [
            ['key' => 'title', 'label' => 'Case study', 'type' => 'person', 'sub' => 'summary', 'photo' => 'cover'],
            ['key' => 'category', 'label' => 'Category', 'type' => 'badge', 'map' => PortfolioItem::CATEGORIES],
            ['key' => 'client.company', 'label' => 'Client', 'type' => 'text'],
            ['key' => 'metric_value', 'label' => 'Result', 'type' => 'render', 'render' => fn (PortfolioItem $p) => $p->metric_value ? '<strong>' . e($p->metric_value) . '</strong> <small class="muted">' . e($p->metric_label) . '</small>' : '—'],
            ['key' => 'is_featured', 'label' => 'Featured', 'type' => 'bool'],
            ['key' => 'is_published', 'label' => 'Published', 'type' => 'bool'],
        ];
    }

    protected function fields(?Model $record = null): array
    {
        return [
            ['name' => 'title', 'label' => 'Title', 'type' => 'text', 'rules' => 'required|string|max:160', 'col' => 8],
            ['name' => 'category', 'label' => 'Category', 'type' => 'select', 'required' => true, 'options' => PortfolioItem::CATEGORIES, 'col' => 4],
            ['name' => 'summary', 'label' => 'One-line summary', 'type' => 'text', 'rules' => 'required|string|max:255', 'col' => 12],
            ['name' => 'description', 'label' => 'Story / description', 'type' => 'textarea', 'rows' => 6, 'rules' => 'nullable|string|max:8000', 'col' => 12],
            ['name' => 'client_id', 'label' => 'Client', 'type' => 'select', 'col' => 4,
                'options' => Client::orderBy('company')->get()->mapWithKeys(fn ($c) => [$c->id => $c->company ?: $c->name])->all(), 'rules' => 'nullable|exists:clients,id'],
            ['name' => 'year', 'label' => 'Year', 'type' => 'number', 'rules' => 'nullable|integer|min:2000|max:2100', 'default' => date('Y'), 'col' => 2],
            ['name' => 'metric_value', 'label' => 'Result number', 'type' => 'text', 'rules' => 'nullable|string|max:30', 'col' => 3, 'placeholder' => '+240%'],
            ['name' => 'metric_label', 'label' => 'Result label', 'type' => 'text', 'rules' => 'nullable|string|max:60', 'col' => 3, 'placeholder' => 'qualified leads'],
            ['name' => 'tags', 'label' => 'Tech / tags', 'type' => 'tags', 'col' => 12, 'help' => 'Comma-separated, e.g. Laravel, Flutter, Meta Ads'],
            ['name' => 'cover', 'label' => 'Cover image', 'type' => 'image', 'folder' => 'portfolio', 'rules' => 'nullable|image|max:6144', 'col' => 6,
                'help' => 'Optional — without one we generate artwork from the colour theme.'],
            ['name' => 'theme', 'label' => 'Artwork colour theme', 'type' => 'select', 'required' => true, 'col' => 6,
                'options' => array_combine(PortfolioItem::THEMES, array_map('ucfirst', PortfolioItem::THEMES))],
            ['name' => 'project_url', 'label' => 'Live URL', 'type' => 'url', 'rules' => 'nullable|url|max:255', 'col' => 6],
            ['name' => 'sort_order', 'label' => 'Display order', 'type' => 'number', 'rules' => 'nullable|integer|min:0', 'default' => 0, 'col' => 2],
            ['name' => 'is_featured', 'label' => 'Featured on home', 'type' => 'checkbox', 'col' => 2],
            ['name' => 'is_published', 'label' => 'Published', 'type' => 'checkbox', 'default' => true, 'col' => 2],
        ];
    }

    protected function prepare(array $data, ?Model $record, Request $request): array
    {
        $data['slug'] = $record && $record->title === $data['title'] ? $record->slug : $this->uniqueSlug($data['title'], $record);
        $data['sort_order'] = $data['sort_order'] ?? 0;

        return $data;
    }
}
