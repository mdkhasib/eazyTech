<!doctype html>
<html lang="en" data-theme="dark"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Access denied</title><link rel="icon" href="{{ asset('images/favicon.png') }}">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Space+Grotesk:wght@700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/admin.css') }}"></head>
<body><div class="err-page"><div>
<img src="{{ asset('images/logo-dark.png') }}" alt="" style="height:54px;margin-bottom:24px">
<h1>403</h1><h2 style="margin:8px 0 10px">Access denied</h2>
<p class="muted" style="max-width:420px;margin:0 auto 24px">{{ $exception->getMessage() ?: 'Your role does not have access to this section. Ask a Super Admin to update your permissions.' }}</p>
<a class="btn primary" href="{{ url('/') }}">Back to home</a>
</div></div></body></html>
