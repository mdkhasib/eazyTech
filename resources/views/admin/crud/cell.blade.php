@php
    $type = $c['type'] ?? 'text';
    $val = data_get($row, $c['key']);
@endphp
@switch($type)
    @case('person')
        @php
            $photo = isset($c['photo']) ? data_get($row, $c['photo']) : null;
            $nameForInitials = data_get($row, $c['fallback'] ?? $c['key']);
        @endphp
        <div class="person">
            @if($photo)<img class="avatar sq" src="{{ asset($photo) }}" alt="">@else<span class="avatar sq">{{ initials($nameForInitials) }}</span>@endif
            <div><b>{{ $val }}</b>@if(!empty($c['sub']))<small>{{ data_get($row, $c['sub']) }}</small>@endif</div>
        </div>
        @break
    @case('title')
        <b>{{ $val }}</b>@if(!empty($c['sub']))<br><small class="muted">{{ \Illuminate\Support\Str::limit((string) data_get($row, $c['sub']), 70) }}</small>@endif
        @break
    @case('badge')
        @if($val !== null && $val !== '')<span class="badge {{ badge_color((string) $val) }}">{{ ($c['map'][$val] ?? null) ?: ucfirst(str_replace('_', ' ', (string) $val)) }}</span>@endif
        @break
    @case('bool')
        @if($val)<span class="badge green">Yes</span>@else<span class="badge">No</span>@endif
        @break
    @case('date')
        {{ $val ? \Illuminate\Support\Carbon::parse($val)->format('d M Y') : '—' }}
        @break
    @case('money')
        {{ $val !== null ? money($val) : '—' }}
        @break
    @case('progress')
        <span class="bar"><i style="width:{{ (int) $val }}%"></i></span> <small class="muted">{{ (int) $val }}%</small>
        @break
    @case('render')
        {!! ($c['render'])($row) !!}
        @break
    @default
        {{ $val !== null && $val !== '' ? $val : '—' }}
@endswitch
