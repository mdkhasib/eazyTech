@extends('site.layout')
@section('title', $item->title . ' — Case study · ' . site('site_name'))
@section('description', $item->summary)

@section('content')
@include('site.partials.page-hero', [
    'crumbs' => ['Our Work' => route('site.portfolio'), $item->title => null],
    'eyebrow' => $item->category_label . ' · Case study',
    'title' => e($item->title),
    'lead' => $item->summary,
])

<section class="sec tight">
    <div class="wrap">
        <div class="proj-hero-art reveal zoom">@include('site.partials.art', ['item' => $item])</div>

        <div class="proj-meta reveal">
            <div><small>Client</small><b>{{ $item->client?->company ?? 'Confidential' }}</b></div>
            <div><small>Category</small><b>{{ $item->category_label }}</b></div>
            <div><small>Year</small><b>{{ $item->year ?? '—' }}</b></div>
            <div><small>Industry</small><b>{{ $item->client?->industry ?? '—' }}</b></div>
        </div>

        <div class="detail" style="grid-template-columns:1.5fr 1fr">
            <div class="prose">
                <h2 class="reveal">The story</h2>
                <p class="reveal">{{ $item->description ?: $item->summary }}</p>
                @if($item->tags)
                    <h2 class="reveal" style="font-size:26px;margin-top:40px">Built with</h2>
                    <div class="tags reveal" style="margin-top:0">@foreach($item->tags as $t)<span>{{ $t }}</span>@endforeach</div>
                @endif
                @if($item->project_url)<p style="margin-top:30px"><a class="btn" href="{{ $item->project_url }}" target="_blank" rel="noopener">Visit live project <x-icon name="arrow-up-right" :size="17" /></a></p>@endif
            </div>
            @if($item->metric_value)
                <div class="result-card reveal right"><b class="grad">{{ $item->metric_value }}</b><span>{{ $item->metric_label }}</span></div>
            @endif
        </div>
    </div>
</section>

@if($others->isNotEmpty())
<section class="sec tight" style="background:var(--bg-2)">
    <div class="wrap">
        <div class="block-title reveal"><h2>More <span class="grad">projects</span></h2></div>
        <div class="work-grid">
            @foreach($others as $o)
                <a href="{{ route('site.project', $o->slug) }}" class="work reveal">
                    <div class="art-wrap">@include('site.partials.art', ['item' => $o])<span class="art-badge">{{ $o->category_label }}</span></div>
                    <div class="work-body"><h3>{{ $o->title }}</h3><p>{{ $o->summary }}</p></div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif
@include('site.partials.cta')
@endsection
