@php
    $sum = (int) collect($data)->sum('value');
    $total = max(1, $sum);
    $acc = 0; $stops = [];
    foreach ($data as $i => $d) {
        $start = round($acc / $total * 100, 2);
        $acc += $d['value'];
        $end = round($acc / $total * 100, 2);
        $stops[] = ($colors[$i % count($colors)]) . " {$start}% {$end}%";
    }
    $bg = $sum ? 'conic-gradient(' . implode(',', $stops) . ')' : 'var(--hover)';
@endphp
<div class="donut-wrap">
    <div class="donut" style="background: {{ $bg }}"><b><span>{{ $sum }}<small>{{ $caption ?? 'total' }}</small></span></b></div>
    <div class="legend">
        @foreach($data as $i => $d)
            <div><span class="dot" style="background: {{ $colors[$i % count($colors)] }}"></span> {{ $d['label'] }} <b>{{ $d['value'] }}</b></div>
        @endforeach
    </div>
</div>
