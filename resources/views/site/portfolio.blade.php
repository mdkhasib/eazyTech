@extends('site.layout')
@section('title', 'Our Work — Case studies · ' . site('site_name'))
@section('description', 'Selected web apps, mobile apps, ERP systems, e-commerce stores and marketing campaigns delivered by EazyTech Solution.')

@section('content')
@include('site.partials.page-hero', [
    'crumbs' => ['Our Work' => null],
    'eyebrow' => 'Portfolio',
    'title' => 'Work that <span class="grad">moves the needle.</span>',
    'lead' => 'A selection of products, platforms and campaigns we have planned, built and grown with our clients.',
])

<section class="sec bg-glow">
    <div class="wrap">
        <div class="chips reveal" data-filter-group="work">
            <button class="chip on" data-filter="all">All projects</button>
            @foreach($categories as $key => $label)
                @if($items->where('category', $key)->isNotEmpty())<button class="chip" data-filter="{{ $key }}">{{ $label }}</button>@endif
            @endforeach
        </div>
        <div class="work-grid">
            @forelse($items as $i => $item)
                <a href="{{ route('site.project', $item->slug) }}" class="work reveal" data-cat="{{ $item->category }}" style="--d:{{ ($i % 3) * .08 }}s">
                    <div class="art-wrap">
                        @include('site.partials.art', ['item' => $item])
                        <span class="art-badge">{{ $item->category_label }}</span>
                        @if($item->metric_value)<span class="metric"><b class="grad-2">{{ $item->metric_value }}</b><small>{{ $item->metric_label }}</small></span>@endif
                    </div>
                    <div class="work-body">
                        <h3>{{ $item->title }}</h3><p>{{ $item->summary }}</p>
                        <div class="tags">@foreach(array_slice($item->tags ?? [], 0, 4) as $t)<span>{{ $t }}</span>@endforeach</div>
                    </div>
                </a>
            @empty
                <p class="empty-note" style="grid-column:1/-1">Case studies are coming soon.</p>
            @endforelse
        </div>
    </div>
</section>
@include('site.partials.cta')
@endsection
