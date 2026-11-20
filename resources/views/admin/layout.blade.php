<!doctype html>
<html lang="en" data-theme="dark">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'Admin') · {{ site('site_name', 'EazyTech Solution') }}</title>
<link rel="icon" href="{{ asset('images/favicon.png') }}">
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/admin.css') }}?v={{ filemtime(public_path('css/admin.css')) }}">
<script>try{var t=localStorage.getItem('ez-admin-theme');if(t)document.documentElement.setAttribute('data-theme',t)}catch(e){}</script>
</head>
<body>
@php
    $user = auth()->user();
    $nav = [
        'Overview' => [['Dashboard', 'admin.dashboard', 'dashboard', 'dashboard']],
        'Work' => [
            ['Tasks', 'admin.tasks.index', 'tasks', 'check'],
            ['Projects', 'admin.projects.index', 'projects', 'folder'],
            ['Clients', 'admin.clients.index', 'clients', 'briefcase'],
        ],
        'People & HR' => [
            ['Team', 'admin.team.index', 'team', 'users'],
            ['Departments', 'admin.departments.index', 'team', 'layers'],
            ['HR & payroll', 'admin.hr', 'hr', 'dollar'],
            ['Work logs', 'admin.worklogs.index', 'hr', 'clock'],
            ['Leave requests', 'admin.leaves.index', 'hr', 'calendar'],
            ['Job openings', 'admin.jobs.index', 'careers', 'briefcase'],
            ['Applications', 'admin.applications.index', 'careers', 'user'],
        ],
        'Website' => [
            ['Portfolio', 'admin.portfolio.index', 'portfolio', 'layers'],
            ['Services', 'admin.services.index', 'services', 'rocket'],
            ['Testimonials', 'admin.testimonials.index', 'testimonials', 'star'],
            ['Leads & messages', 'admin.messages.index', 'messages', 'mail'],
        ],
        'Access' => [
            ['Users', 'admin.users.index', 'users', 'shield'],
            ['Roles & permissions', 'admin.roles.index', 'roles', 'lock'],
            ['Settings', 'admin.settings', 'settings', 'settings'],
        ],
    ];
    $newLeads = $user->hasPermission('messages') ? \App\Models\ContactMessage::where('status', 'new')->count() : 0;
@endphp
<div class="app">
    <aside class="side" id="side">
        <a class="brand" href="{{ route('admin.dashboard') }}">
            <img class="logo-dark" src="{{ asset('images/logo-dark.png') }}" alt="EazyTech Solution">
            <img class="logo-light" src="{{ asset('images/logo.png') }}" alt="EazyTech Solution">
        </a>
        <nav class="nav">
            @foreach($nav as $group => $items)
                @php $visible = collect($items)->filter(fn ($i) => $user->hasPermission($i[2])); @endphp
                @if($visible->isNotEmpty())
                    <div class="nav-label">{{ $group }}</div>
                    @foreach($visible as [$label, $route, $perm, $icon])
                        @php
                            $pattern = str_ends_with($route, '.index') ? \Illuminate\Support\Str::beforeLast($route, '.index') . '.*' : $route;
                            $on = request()->routeIs($pattern);
                        @endphp
                        <a href="{{ route($route) }}" class="{{ $on ? 'on' : '' }}">
                            <x-icon :name="$icon" :size="18" /> {{ $label }}
                            @if($route === 'admin.messages.index' && $newLeads)<span class="count">{{ $newLeads }}</span>@endif
                        </a>
                    @endforeach
                @endif
            @endforeach
        </nav>
        <div class="side-foot">
            <a href="{{ route('site.home') }}" target="_blank" class="btn sm" style="width:100%;justify-content:center"><x-icon name="globe" :size="15" /> View website</a>
            <p style="margin:12px 0 0">© {{ date('Y') }} {{ site('site_name', 'EazyTech Solution') }}</p>
        </div>
    </aside>
    <div class="scrim" id="scrim"></div>

    <div class="main">
        <header class="top">
            <button class="icon-btn burger" id="burger" aria-label="Menu"><x-icon name="menu" /></button>
            <h1>@yield('title', 'Admin')</h1>
            <div class="spacer"></div>
            <button class="icon-btn" id="theme" title="Toggle theme" aria-label="Toggle theme"><x-icon name="sun" /></button>
            <div class="user-chip">
                @if($user->avatar)<img class="avatar" src="{{ asset($user->avatar) }}" alt="">@else<span class="avatar">{{ $user->initials }}</span>@endif
                <div class="who"><b>{{ $user->name }}</b><small>{{ $user->role?->name ?? 'No role' }}</small></div>
                <form method="post" action="{{ route('admin.logout') }}">@csrf
                    <button class="icon-btn" style="border:0;width:34px;height:34px" title="Sign out" aria-label="Sign out"><x-icon name="logout" :size="17" /></button>
                </form>
            </div>
        </header>

        <main class="content">
            @if(session('success'))<div class="flash ok" data-autohide><x-icon name="check" :size="18" /> {{ session('success') }}</div>@endif
            @if(session('error'))<div class="flash bad"><x-icon name="x" :size="18" /> {{ session('error') }}</div>@endif
            @if($errors->any() && ! request()->routeIs('admin.login'))<div class="flash bad"><x-icon name="x" :size="18" /> Please fix the highlighted fields below.</div>@endif
            @yield('content')
        </main>
    </div>
</div>
<script src="{{ asset('js/admin.js') }}?v={{ filemtime(public_path('js/admin.js')) }}"></script>
@stack('scripts')
</body>
</html>
