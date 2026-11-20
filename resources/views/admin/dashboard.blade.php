@extends('admin.layout')
@section('title', 'Dashboard')

@section('content')
@php
    $hour = (int) now()->format('H');
    $greet = $hour < 12 ? 'Good morning' : ($hour < 18 ? 'Good afternoon' : 'Good evening');
    $maxLead = max(1, $leads->max('value'));
    $showMine = $member && $myTasks->isNotEmpty();
@endphp
<div class="page-head">
    <div>
        <h2>{{ $greet }}, {{ \Illuminate\Support\Str::before(auth()->user()->name, ' ') }} 👋</h2>
        <p>Here is what is happening across {{ site('site_name', 'EazyTech Solution') }} today.</p>
    </div>
    <div style="display:flex;gap:10px;flex-wrap:wrap">
        @if(auth()->user()->hasPermission('tasks'))<a href="{{ route('admin.tasks.create') }}" class="btn"><x-icon name="plus" :size="16" /> New task</a>@endif
        @if(auth()->user()->hasPermission('clients'))<a href="{{ route('admin.clients.create') }}" class="btn primary"><x-icon name="plus" :size="16" /> New client</a>@endif
    </div>
</div>

<div class="grid g-6" style="margin-bottom:18px">
    @foreach($stats as $s)
        <div class="card stat tone-{{ $s['tone'] }}">
            <div class="ic"><x-icon :name="$s['icon']" :size="21" /></div>
            <b data-count="{{ $s['value'] }}">{{ $s['value'] }}</b>
            <span>{{ $s['label'] }}</span>
            <small>{{ $s['hint'] }}</small>
        </div>
    @endforeach
</div>

<div class="grid g-2" style="margin-bottom:18px">
    <div class="card">
        <div class="card-h"><h3>Leads — last 6 months</h3><span class="muted">website enquiries</span></div>
        <div class="card-b">
            <div class="bars">
                @foreach($leads as $l)
                    <div class="col"><em>{{ $l['value'] }}</em><i data-h="{{ round($l['value'] / $maxLead * 100) }}"></i><span>{{ $l['label'] }}</span></div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-h"><h3>Task pipeline</h3></div>
        <div class="card-b">
            @include('admin.partials.donut', ['data' => $taskStatus, 'colors' => ['#6b7799', '#0db5bc', '#f28a2a', '#34d399'], 'caption' => 'tasks'])
        </div>
    </div>
</div>

<div class="grid g-3" style="margin-bottom:18px">
    <div class="card">
        <div class="card-h"><h3>Active projects</h3>@if(auth()->user()->hasPermission('projects'))<a href="{{ route('admin.projects.index') }}">View all</a>@endif</div>
        <div class="card-b">
            @forelse($projects as $p)
                <div class="list-row">
                    <div class="grow"><b>{{ $p->title }}</b><small>{{ $p->client?->company }} · due {{ $p->due_date?->format('d M') ?? '—' }}</small></div>
                    <div style="text-align:right"><span class="bar"><i style="width:{{ $p->progress }}%"></i></span><br><small class="muted">{{ $p->progress }}%</small></div>
                </div>
            @empty<div class="empty">No active projects.</div>@endforelse
        </div>
    </div>

    <div class="card">
        <div class="card-h"><h3>{{ $showMine ? 'My open tasks' : 'Upcoming deadlines' }}</h3>@if(auth()->user()->hasPermission('tasks'))<a href="{{ route('admin.tasks.index') }}">Board</a>@endif</div>
        <div class="card-b">
            @php $list = $showMine ? $myTasks : $upcoming; @endphp
            @forelse($list as $t)
                <div class="list-row">
                    <span class="badge {{ badge_color($t->priority) }}" style="padding:3px 8px">{{ ucfirst($t->priority) }}</span>
                    <div class="grow"><b>{{ $t->title }}</b><small class="{{ $t->is_overdue ? 'late' : '' }}">{{ $t->project?->title }} · {{ $t->due_date?->format('d M') ?? 'no date' }}{{ $t->is_overdue ? ' · overdue' : '' }}</small></div>
                </div>
            @empty<div class="empty">You are all caught up 🎉</div>@endforelse
        </div>
    </div>

    <div class="card">
        <div class="card-h"><h3>Team mix</h3>@if(auth()->user()->hasPermission('team'))<a href="{{ route('admin.team.index') }}">Manage</a>@endif</div>
        <div class="card-b">
            @include('admin.partials.donut', ['data' => $teamMix, 'colors' => ['#0db5bc', '#f28a2a', '#ec5a5e', '#f2d624', '#8b7cff'], 'caption' => 'people'])
            @if($pendingLeaves->isNotEmpty())
                <div style="margin-top:18px;padding-top:14px;border-top:1px solid var(--line)">
                    <small class="muted" style="text-transform:uppercase;letter-spacing:.1em;font-weight:600">Pending leave</small>
                    @foreach($pendingLeaves as $lv)
                        <div class="list-row"><div class="grow"><b>{{ $lv->member->name }}</b><small>{{ $lv->start_date->format('d M') }} → {{ $lv->end_date->format('d M') }} · {{ ucfirst($lv->type) }}</small></div><span class="badge yellow">Pending</span></div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

@if(auth()->user()->hasPermission('messages'))
<div class="card">
    <div class="card-h"><h3>Latest website enquiries</h3><a href="{{ route('admin.messages.index') }}">Open inbox</a></div>
    <div class="tbl-wrap"><table class="tbl" style="margin-top:10px">
        <tbody>
        @foreach($recentMessages as $m)
            <tr>
                <td><div class="person"><span class="avatar sq">{{ initials($m->name) }}</span><div><b>{{ $m->name }}</b><small>{{ $m->email }}</small></div></div></td>
                <td>{{ $m->service ?: '—' }}</td>
                <td class="muted">{{ $m->created_at->diffForHumans() }}</td>
                <td><span class="badge {{ badge_color($m->status) }}">{{ ucfirst($m->status) }}</span></td>
            </tr>
        @endforeach
        </tbody>
    </table></div>
</div>
@endif
@endsection
