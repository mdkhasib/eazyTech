@extends('site.layout')
@section('title', $service->title . ' · ' . site('site_name'))
@section('description', $service->short_description)

@section('content')
@include('site.partials.page-hero', [
    'crumbs' => ['Services' => route('site.services'), $service->title => null],
    'eyebrow' => $service->category === 'software' ? 'Software & Apps' : 'Marketing & Social',
    'title' => e($service->title),
    'lead' => $service->short_description,
])

<section class="sec">
    <div class="wrap detail">
        <div class="prose">
            <h2 class="reveal">What you get</h2>
            <p class="reveal">{{ $service->description }}</p>
            @if($service->features)
                <div class="feat-grid">
                    @foreach($service->features as $i => $f)
                        <div class="feat reveal" style="--d:{{ ($i % 2) * .08 }}s"><span class="tick"><x-icon name="check" :size="15" /></span>{{ $f }}</div>
                    @endforeach
                </div>
            @endif

            <h2 class="reveal">How it works</h2>
            <div class="mini-steps reveal">
                <div><b>01</b><span>Discover &amp; plan</span></div>
                <div><b>02</b><span>Design &amp; approve</span></div>
                <div><b>03</b><span>Build in sprints</span></div>
                <div><b>04</b><span>Launch &amp; grow</span></div>
            </div>
        </div>

        <aside class="side-card reveal right">
            <div class="ib-lg"><x-icon :name="$service->icon" :size="32" /></div>
            <h3>Get a quote for {{ $service->title }}</h3>
            <p>Share your idea — we reply within one business day with a clear plan, timeline and price.</p>
            <a href="#contact" class="btn primary block magnetic">Start this project <x-icon name="arrow-right" :size="18" /></a>
            <ul class="side-list">
                <li><a href="mailto:{{ site('email') }}"><span>{{ site('email') }}</span><x-icon name="mail" :size="17" /></a></li>
                <li><a href="tel:{{ preg_replace('/[^\d+]/', '', (string) site('phone')) }}"><span>{{ site('phone') }}</span><x-icon name="call" :size="17" /></a></li>
            </ul>
        </aside>
    </div>
</section>

@if($related->isNotEmpty())
<section class="sec tight" style="background:var(--bg-2)">
    <div class="wrap">
        <div class="block-title reveal"><h2>Related <span class="grad">services</span></h2></div>
        <div class="svc-grid three">
            @foreach($related as $s)
                <a href="{{ route('site.service', $s->slug) }}" class="svc spot reveal">
                    <span class="ib"><x-icon :name="$s->icon" :size="26" /></span><h3>{{ $s->title }}</h3><p>{{ $s->short_description }}</p>
                    <span class="more">Explore <x-icon name="arrow-right" :size="16" /></span>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

@if($work->isNotEmpty())
<section class="sec tight">
    <div class="wrap">
        <div class="block-title reveal"><h2>Recent <span class="grad">work</span></h2></div>
        <div class="work-grid">
            @foreach($work as $item)
                <a href="{{ route('site.project', $item->slug) }}" class="work reveal">
                    <div class="art-wrap">@include('site.partials.art', ['item' => $item])<span class="art-badge">{{ $item->category_label }}</span></div>
                    <div class="work-body"><h3>{{ $item->title }}</h3><p>{{ $item->summary }}</p></div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

@include('site.partials.cta', ['preselect' => $service->title])
@endsection
