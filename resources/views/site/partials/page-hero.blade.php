<section class="page-hero">
    <div class="gridlines"></div><span class="orb a"></span><span class="orb b"></span>
    <div class="wrap">
        <nav class="crumbs" aria-label="Breadcrumb">
            <a href="{{ route('site.home') }}">Home</a>
            @foreach(($crumbs ?? []) as $label => $url)
                <span>/</span> @if($url)<a href="{{ $url }}">{{ $label }}</a>@else<b>{{ $label }}</b>@endif
            @endforeach
        </nav>
        <span class="eyebrow reveal">{{ $eyebrow }}</span>
        <h1 class="reveal" style="--d:.08s">{!! $title !!}</h1>
        @isset($lead)<p class="lead reveal" style="--d:.16s">{{ $lead }}</p>@endisset
        {{ $slot ?? '' }}
    </div>
</section>
