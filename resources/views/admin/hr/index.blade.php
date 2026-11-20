@extends('admin.layout')
@section('title', 'HR & payroll')

@section('content')
<div class="page-head">
    <div><h2>HR &amp; payroll</h2><p>Permanent staff on monthly salary, remote part-timers paid by approved hours.</p></div>
    <div class="month-nav">
        <a class="btn sm icon" href="{{ route('admin.hr', ['month' => $prev]) }}" title="Previous month">‹</a>
        <b>{{ $month->format('F Y') }}</b>
        <a class="btn sm icon" href="{{ route('admin.hr', ['month' => $next]) }}" title="Next month">›</a>
    </div>
</div>

<div class="grid g-4" style="margin-bottom:18px">
    <div class="card stat tone-teal"><div class="ic"><x-icon name="users" :size="21" /></div><b>{{ $summary['permanent'] }}</b><span>Permanent staff</span><small>{{ money($summary['permanent_pay']) }} / month</small></div>
    <div class="card stat tone-orange"><div class="ic"><x-icon name="clock" :size="21" /></div><b>{{ $summary['part_time'] }}</b><span>Remote part-time</span><small>{{ rtrim(rtrim(number_format($summary['hours'], 1), '0'), '.') }}h approved · {{ rtrim(rtrim(number_format($summary['pending_hours'], 1), '0'), '.') }}h pending</small></div>
    <div class="card stat tone-yellow"><div class="ic"><x-icon name="calendar" :size="21" /></div><b>{{ $summary['pending_leaves'] }}</b><span>Pending leave requests</span><small><a href="{{ route('admin.leaves.index', ['status' => 'pending']) }}">Review now →</a></small></div>
    <div class="card stat tone-coral"><div class="ic"><x-icon name="user" :size="21" /></div><b>{{ $summary['new_applications'] }}</b><span>New job applications</span><small><a href="{{ route('admin.applications.index', ['status' => 'new']) }}">Open pipeline →</a></small></div>
</div>

<div class="card" style="margin-bottom:18px">
    <div class="card-b" style="display:flex;align-items:center;justify-content:space-between;gap:20px;flex-wrap:wrap">
        <div><span class="muted">Estimated payroll — {{ $month->format('F Y') }}</span><div class="payroll-total">{{ money($summary['payroll']) }}</div></div>
        <div style="display:flex;gap:30px;flex-wrap:wrap">
            <div><small class="muted">Salaried</small><br><b style="font:700 20px var(--display)">{{ money($summary['permanent_pay']) }}</b></div>
            <div><small class="muted">Hourly (approved hours)</small><br><b style="font:700 20px var(--display)">{{ money($summary['part_time_pay']) }}</b></div>
        </div>
        <div style="display:flex;gap:10px;flex-wrap:wrap">
            <a href="{{ route('admin.worklogs.create') }}" class="btn"><x-icon name="plus" :size="16" /> Log hours</a>
            <a href="{{ route('admin.leaves.create') }}" class="btn"><x-icon name="plus" :size="16" /> Add leave</a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-h"><h3>Payroll sheet</h3><span class="muted">pending hours are not paid until approved</span></div>
    <div class="tbl-wrap"><table class="tbl" style="margin-top:10px">
        <thead><tr><th>Member</th><th>Type</th><th>Basis</th><th>Hours (approved / pending)</th><th style="text-align:right">Pay</th></tr></thead>
        <tbody>
        @foreach($rows as $r)
            @php $m = $r['member']; @endphp
            <tr>
                <td><div class="person">
                    @if($m->photo)<img class="avatar sq" src="{{ asset($m->photo) }}" alt="">@else<span class="avatar sq">{{ $m->initials }}</span>@endif
                    <div><b>{{ $m->name }}</b><small>{{ $m->designation }}</small></div></div></td>
                <td><span class="badge {{ badge_color($m->employment_type) }}">{{ $m->type_label }}</span></td>
                <td>{{ $r['hourly'] ? money($m->hourly_rate, 2) . ' / hour' : ($m->monthly_salary ? money($m->monthly_salary) . ' / month' : '—') }}</td>
                <td>
                    @if($r['hourly'])
                        <b>{{ rtrim(rtrim(number_format($r['approved'], 1), '0'), '.') ?: '0' }}h</b>
                        <span class="muted">/ {{ rtrim(rtrim(number_format($r['pending'], 1), '0'), '.') ?: '0' }}h pending</span>
                        <br><span class="bar" style="width:140px"><i style="width:{{ $r['target'] ? min(100, round($r['approved'] / $r['target'] * 100)) : 0 }}%"></i></span>
                        <small class="muted">target ≈ {{ round($r['target']) }}h</small>
                    @else <span class="muted">Fixed salary</span> @endif
                </td>
                <td style="text-align:right"><b>{{ money($r['pay'], $r['hourly'] ? 2 : 0) }}</b></td>
            </tr>
        @endforeach
        </tbody>
    </table></div>
</div>
@endsection
