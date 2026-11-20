@extends('site.layout')
@section('title', 'About us · ' . site('site_name'))
@section('description', 'Meet the EazyTech Solution team — permanent experts and flexible remote specialists planning and building software and growth for businesses worldwide.')

@section('content')
@include('site.partials.page-hero', [
    'crumbs' => ['About' => null],
    'eyebrow' => 'About EazyTech',
    'title' => 'A studio of builders, designers &amp; <span class="grad">growth hackers.</span>',
    'lead' => 'We started with a simple belief: technology should be easy. Today we help businesses of every size plan, build and grow with software and marketing that actually works.',
])

<section class="sec">
    <div class="wrap split">
        <div class="reveal left">
            <span class="eyebrow">Our story</span>
            <h2 style="font-size:clamp(32px,4vw,52px);margin:20px 0 18px">Why <span class="grad">"Eazy"</span>? Because good tech should feel that way.</h2>
            <p class="muted" style="font-size:18px;margin-bottom:18px">Our logo is a lightbulb made of puzzle pieces — because every business problem is a puzzle, and the right idea clicks everything into place. We bring together engineers, designers, marketers and AI specialists to solve it end to end.</p>
            <p class="muted" style="font-size:18px">From a neighbourhood pharmacy that needs batch tracking to a retail brand scaling across cities, we build systems that are fast, secure and genuinely easy to use — and campaigns that fill them with customers.</p>
            <div class="hero-proof" style="margin-top:34px">
                <div><b><span data-count="{{ (int) site('stat_projects', 120) }}">0</span>+</b><span>Projects</span></div>
                <div><b><span data-count="{{ (int) site('stat_clients', 60) }}">0</span>+</b><span>Clients</span></div>
                <div><b><span data-count="{{ (int) site('stat_team', 25) }}">0</span>+</b><span>Specialists</span></div>
            </div>
        </div>
        <div class="about-visual reveal right"><span class="glow"></span><div class="card-lg"><img src="{{ asset('images/logo-icon.png') }}" alt=""></div></div>
    </div>
</section>

<section class="sec tight" style="background:var(--bg-2)">
    <div class="wrap">
        <div class="sec-head center reveal"><span class="eyebrow">What drives us</span><h2>Our <span class="grad">values</span></h2></div>
        <div class="values">
            @foreach([['target', 'Outcomes over output', 'We measure success by your revenue, savings and growth — not lines of code or posts published.'], ['zap', 'Speed with quality', 'Short sprints, weekly demos and rigorous testing so you get fast and reliable.'], ['shield', 'Radical honesty', 'Clear scopes, honest timelines and straight answers. No jargon, no surprises.'], ['heart_', 'Craft & care', 'We sweat the details — from database indexes to button micro-interactions.']] as $i => [$ic, $t, $d])
                <div class="reveal" style="--d:{{ $i * .08 }}s"><div class="ib"><x-icon :name="$ic === 'heart_' ? 'award' : $ic" :size="24" /></div><h3>{{ $t }}</h3><p>{{ $d }}</p></div>
            @endforeach
        </div>
    </div>
</section>

<section class="sec">
    <div class="wrap">
        <div class="sec-head center reveal"><span class="eyebrow">How we're organised</span><h2>Permanent core. <span class="grad">Flexible remote talent.</span></h2><p>A stable in-house team keeps quality and knowledge consistent, while vetted remote part-timers let us scale up the exact skills your project needs.</p></div>
        <div class="model">
            <div class="pm reveal left"><span class="pill">Permanent team</span><h3>The core crew</h3><p>Full-time engineers, project managers, designers and marketers who own your product from kickoff to long-term support.</p>
                <ul><li><span class="tick"><x-icon name="check" :size="15" /></span>Dedicated project ownership</li><li><span class="tick"><x-icon name="check" :size="15" /></span>Deep domain knowledge</li><li><span class="tick"><x-icon name="check" :size="15" /></span>Hybrid &amp; on-site collaboration</li></ul></div>
            <div class="pt reveal right"><span class="pill">Remote part-time</span><h3>On-demand specialists</h3><p>Flutter developers, UI/UX designers, AI video editors and social media experts working remotely on flexible hours.</p>
                <ul><li><span class="tick"><x-icon name="check" :size="15" /></span>Scale up or down per project</li><li><span class="tick"><x-icon name="check" :size="15" /></span>Hour-by-hour transparent timesheets</li><li><span class="tick"><x-icon name="check" :size="15" /></span>Work from anywhere in the world</li></ul></div>
        </div>
    </div>
</section>

<section class="sec bg-glow alt" style="background:var(--bg-2)" id="team">
    <div class="wrap">
        <div class="sec-head center reveal"><span class="eyebrow">The team</span><h2>People who <span class="grad">make it happen</span></h2></div>
        <div class="team-grid">@foreach($team as $i => $m) @include('site.partials.member', ['m' => $m, 'i' => $i]) @endforeach</div>
        <div style="text-align:center;margin-top:50px" class="reveal"><a href="{{ route('site.careers') }}" class="btn primary magnetic">Join our team <x-icon name="arrow-right" :size="18" /></a></div>
    </div>
</section>
@include('site.partials.cta')
@endsection
