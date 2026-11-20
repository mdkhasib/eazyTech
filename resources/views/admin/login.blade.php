<!doctype html>
<html lang="en" data-theme="dark">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Sign in · {{ site('site_name', 'EazyTech Solution') }}</title>
<link rel="icon" href="{{ asset('images/favicon.png') }}">
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/admin.css') }}?v={{ filemtime(public_path('css/admin.css')) }}">
</head>
<body>
<div class="login">
    <section class="login-art">
        <span class="orb o1"></span><span class="orb o2"></span><span class="orb o3"></span><span class="grid-bg"></span>
        <img class="logo" src="{{ asset('images/logo-dark.png') }}" alt="EazyTech Solution">
        <div>
            <h2>Run the whole studio <span>from one place.</span></h2>
            <p>Team, tasks, clients, portfolio, HR and payroll — your command centre for planning and building great software and campaigns.</p>
        </div>
        <div class="pills"><span>Team &amp; roles</span><span>Kanban tasks</span><span>Client CRM</span><span>HR &amp; payroll</span><span>Portfolio CMS</span></div>
    </section>

    <section class="login-form">
        <div class="login-box">
            <h1>Welcome back</h1>
            <p class="sub">Sign in to the EazyTech admin panel.</p>

            <form method="post" action="{{ route('admin.login.submit') }}" id="login-form">
                @csrf
                <div class="field {{ $errors->has('email') ? 'has-err' : '' }}">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
                    @error('email')<span class="err">{{ $message }}</span>@enderror
                </div>
                <div class="field">
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" required autocomplete="current-password">
                </div>
                <label class="check" style="margin-bottom:18px"><input type="checkbox" name="remember" value="1"> Keep me signed in</label>
                <button class="btn primary" style="width:100%;justify-content:center;padding:13px">Sign in <x-icon name="arrow-right" :size="17" /></button>
            </form>

            @if(count($demo))
            <div class="demo">
                <h5><x-icon name="sparkles" :size="16" /> Demo accounts</h5>
                <p>Click any account to fill the form, then press Sign in. Password for all: <code>{{ \Database\Seeders\DatabaseSeeder::PASSWORD }}</code></p>
                @foreach($demo as $d)
                    <button type="button" data-email="{{ $d['email'] }}">
                        <span class="avatar">{{ initials($d['name']) }}</span>
                        <span><b>{{ $d['role'] }}</b><small>{{ $d['email'] }}</small></span>
                        <span class="go">Use →</span>
                    </button>
                @endforeach
            </div>
            @endif
            <p class="pw-note"><a href="{{ route('site.home') }}">← Back to website</a></p>
        </div>
    </section>
</div>
<script>
document.querySelectorAll('.demo button').forEach(function (b) {
    b.addEventListener('click', function () {
        document.getElementById('email').value = b.dataset.email;
        document.getElementById('password').value = @json(\Database\Seeders\DatabaseSeeder::PASSWORD);
        document.getElementById('login-form').querySelector('button.primary').focus();
    });
});
</script>
</body>
</html>
