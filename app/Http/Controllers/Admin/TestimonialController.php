<?php

namespace App\Http\Controllers\Admin;

use App\Models\Testimonial;
use Illuminate\Database\Eloquent\Model;

class TestimonialController extends ResourceController
{
    protected function model(): string
    {
        return Testimonial::class;
    }

    protected function meta(): array
    {
        return ['title' => 'Testimonials', 'singular' => 'Testimonial', 'route' => 'admin.testimonials', 'icon' => 'star',
            'subtitle' => 'What clients say — shown on the home page.', 'can_create' => true];
    }

    protected function searchable(): array
    {
        return ['name', 'company', 'quote'];
    }

    protected function columns(): array
    {
        return [
            ['key' => 'name', 'label' => 'Client', 'type' => 'person', 'sub' => 'company', 'photo' => 'photo'],
            ['key' => 'quote', 'label' => 'Quote', 'type' => 'render', 'render' => fn (Testimonial $t) => e(\Illuminate\Support\Str::limit($t->quote, 90))],
            ['key' => 'rating', 'label' => 'Rating', 'type' => 'render', 'render' => fn (Testimonial $t) => '<span class="stars">' . str_repeat('★', $t->rating) . '</span>'],
            ['key' => 'is_published', 'label' => 'Published', 'type' => 'bool'],
        ];
    }

    protected function fields(?Model $record = null): array
    {
        return [
            ['name' => 'name', 'label' => 'Name', 'type' => 'text', 'rules' => 'required|string|max:120', 'col' => 6],
            ['name' => 'position', 'label' => 'Position', 'type' => 'text', 'rules' => 'nullable|string|max:120', 'col' => 6],
            ['name' => 'company', 'label' => 'Company', 'type' => 'text', 'rules' => 'nullable|string|max:150', 'col' => 6],
            ['name' => 'rating', 'label' => 'Rating (1–5)', 'type' => 'number', 'rules' => 'required|integer|min:1|max:5', 'default' => 5, 'col' => 3],
            ['name' => 'is_published', 'label' => 'Published', 'type' => 'checkbox', 'default' => true, 'col' => 3],
            ['name' => 'quote', 'label' => 'Quote', 'type' => 'textarea', 'rules' => 'required|string|max:1200', 'col' => 12],
            ['name' => 'photo', 'label' => 'Photo', 'type' => 'image', 'folder' => 'testimonials', 'rules' => 'nullable|image|max:2048', 'col' => 6],
        ];
    }
}
