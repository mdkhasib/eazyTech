@extends('site.layout')
@section('title', 'Services — Software, Apps, ERP & AI Marketing · ' . site('site_name'))
@section('description', 'Web apps, mobile apps, e-commerce, ERP, accounting, HR, shop & pharmacy software, Meta ads, social media and AI video marketing.')

@section('content')
@include('site.partials.page-hero', [
    'crumbs' => ['Services' => null],
    'eyebrow' => 'Our services',
    'title' => 'Everything you need to <span class="grad">go digital and grow.</span>',
    'lead' => 'Custom software, mobile apps and ERP on one side — Meta ads, social media and AI video marketing on the other. One team, one plan, one accountable partner.',
])

<section class="sec bg-glow">
    <div class="wrap">
        <div class="svc-block">
            <div class="block-title reveal"><h2>Software &amp; <span class="grad">Apps</span></h2></div>
            <div class="svc-grid three">
                @foreach($software as $i => $s)
                    <a href="{{ route('site.service', $s->slug) }}" class="svc spot reveal" style="--d:{{ ($i % 3) * .08 }}s">
                        <span class="ib"><x-icon :name="$s->icon" :size="26" /></span>
                        <h3>{{ $s->title }}</h3><p>{{ $s->short_description }}</p>
                        <span class="more">Explore <x-icon name="arrow-right" :size="16" /></span>
                    </a>
                @endforeach
            </div>
        </div>
        <div class="svc-block">
            <div class="block-title reveal"><h2>Marketing, Social &amp; <span class="grad">AI Video</span></h2></div>
            <div class="svc-grid three">
                @foreach($marketing as $i => $s)
                    <a href="{{ route('site.service', $s->slug) }}" class="svc spot reveal" style="--d:{{ ($i % 3) * .08 }}s">
                        <span class="ib"><x-icon :name="$s->icon" :size="26" /></span>
                        <h3>{{ $s->title }}</h3><p>{{ $s->short_description }}</p>
                        <span class="more">Explore <x-icon name="arrow-right" :size="16" /></span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</section>
@include('site.partials.cta')
@endsection
