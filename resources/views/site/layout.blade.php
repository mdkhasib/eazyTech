<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title', site('site_name', 'EazyTech Solution') . ' — Software, Apps & AI Marketing')</title>
<meta name="description" content="@yield('description', 'EazyTech Solution plans and builds web apps, mobile apps, e-commerce, ERP, accounting, HR, shop and pharmacy software — plus Meta ads, social media and AI video marketing that grow your business.')">
<meta name="theme-color" content="#05070e">
<meta property="og:title" content="@yield('title', site('site_name', 'EazyTech Solution'))">
<meta property="og:description" content="@yield('description', 'We plan. We build. We grow your business.')">
<meta property="og:image" content="{{ asset('images/logo.png') }}">
<meta property="og:type" content="website">
<link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png">
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@500;600&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/site.css') }}?v={{ filemtime(public_path('css/site.css')) }}">
<link rel="stylesheet" href="{{ asset('css/site-pages.css') }}?v={{ filemtime(public_path('css/site-pages.css')) }}">
<script>document.documentElement.classList.add('js')</script>
</head>
<body>
<div id="loader"><img src="{{ asset('images/logo-icon.png') }}" alt=""></div>
<div id="progress"></div>
<div id="glow"></div>

<header class="nav" id="nav">
    <div class="wrap">
        <a class="logo" href="{{ route('site.home') }}" aria-label="{{ site('site_name') }} home"><img src="{{ asset('images/logo-dark.png') }}" alt="{{ site('site_name', 'EazyTech Solution') }}"></a>
        <nav class="nav-links" aria-label="Main">
            <a href="{{ route('site.home') }}" class="{{ request()->routeIs('site.home') ? 'on' : '' }}">Home</a>
            <a href="{{ route('site.services') }}" class="{{ request()->routeIs('site.service*') ? 'on' : '' }}">Services</a>
            <a href="{{ route('site.portfolio') }}" class="{{ request()->routeIs('site.portfolio', 'site.project') ? 'on' : '' }}">Our Work</a>
            <a href="{{ route('site.about') }}" class="{{ request()->routeIs('site.about') ? 'on' : '' }}">About</a>
            <a href="{{ route('site.careers') }}" class="{{ request()->routeIs('site.careers') ? 'on' : '' }}">Careers</a>
            <a href="{{ route('site.contact') }}" class="{{ request()->routeIs('site.contact') ? 'on' : '' }}">Contact</a>
        </nav>
        <a href="{{ route('site.contact') }}" class="btn primary sm cta-btn magnetic">Get a free quote <x-icon name="arrow-right" :size="17" /></a>
        <button class="burger" id="burger" aria-label="Open menu"><x-icon name="menu" :size="22" /></button>
    </div>
</header>
<div class="mobile-menu" id="mmenu">
    <button class="burger mobile-close" id="mclose" aria-label="Close menu"><x-icon name="x" :size="22" /></button>
    <a href="{{ route('site.home') }}">Home</a>
    <a href="{{ route('site.services') }}">Services</a>
    <a href="{{ route('site.portfolio') }}">Our Work</a>
    <a href="{{ route('site.about') }}">About</a>
    <a href="{{ route('site.careers') }}">Careers</a>
    <a href="{{ route('site.contact') }}">Contact</a>
</div>

<main>@yield('content')</main>

@php
    $footerServices = \App\Models\Service::active()->take(7)->get(['title', 'slug']);
    $wa = preg_replace('/\D/', '', (string) site('whatsapp', ''));
@endphp
<footer class="footer">
    <div class="wrap">
        <div class="footer-grid">
            <div>
                <a class="logo" href="{{ route('site.home') }}"><img src="{{ asset('images/logo-dark.png') }}" alt="{{ site('site_name') }}"></a>
                <p>{{ site('tagline', 'We plan. We build. We grow your business.') }} Software, mobile apps, ERP and AI-powered marketing — engineered end to end.</p>
                <div class="socials">
                    @foreach(['facebook', 'instagram', 'linkedin', 'youtube'] as $s)
                        @if(site($s))<a href="{{ site($s) }}" target="_blank" rel="noopener" aria-label="{{ ucfirst($s) }}"><x-icon :name="$s" :size="19" /></a>@endif
                    @endforeach
                </div>
            </div>
            <div>
                <h4>Services</h4>
                <ul>@foreach($footerServices as $fs)<li><a href="{{ route('site.service', $fs->slug) }}">{{ $fs->title }}</a></li>@endforeach</ul>
            </div>
            <div>
                <h4>Company</h4>
                <ul>
                    <li><a href="{{ route('site.about') }}">About us</a></li>
                    <li><a href="{{ route('site.portfolio') }}">Our work</a></li>
                    <li><a href="{{ route('site.careers') }}">Careers</a></li>
                    <li><a href="{{ route('site.contact') }}">Contact</a></li>
                    <li><a href="{{ route('admin.login') }}">Team login</a></li>
                </ul>
            </div>
            <div>
                <h4>Let's talk</h4>
                <ul class="contact">
                    <li><x-icon name="mail" :size="18" /><a href="mailto:{{ site('email') }}">{{ site('email') }}</a></li>
                    <li><x-icon name="call" :size="18" /><a href="tel:{{ preg_replace('/[^\d+]/', '', (string) site('phone')) }}">{{ site('phone') }}</a></li>
                    <li><x-icon name="map-pin" :size="18" /><span>{{ site('address') }}</span></li>
                    <li><x-icon name="clock" :size="18" /><span>{{ site('working_hours') }}</span></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <span>© {{ date('Y') }} {{ site('site_name', 'EazyTech Solution') }}. All rights reserved.</span>
            <span>Designed &amp; engineered in-house with <span class="grad">♥</span> and a lot of coffee.</span>
        </div>
    </div>
    <div class="big-word" aria-hidden="true">EAZYTECH</div>
</footer>

<div class="fab">
    @if($wa)<a class="wa" href="https://wa.me/{{ $wa }}" target="_blank" rel="noopener" aria-label="Chat on WhatsApp"><x-icon name="message" :size="26" /></a>@endif
    <button class="top" id="totop" aria-label="Back to top"><x-icon name="arrow-up-right" :size="20" style="transform:rotate(-45deg)" /></button>
</div>

<script src="{{ asset('js/site.js') }}?v={{ filemtime(public_path('js/site.js')) }}"></script>
@stack('scripts')
</body>
</html>
