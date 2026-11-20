@extends('admin.layout')
@section('title', 'Tasks')

@section('content')
<div class="page-head">
    <div><h2>Task board</h2><p>{{ $meta['subtitle'] }}</p></div>
    <a href="{{ route('admin.tasks.create') }}" class="btn primary"><x-icon name="plus" :size="17" /> New task</a>
</div>

<form method="get" class="card toolbar" style="margin-bottom:18px;border-bottom:1px solid var(--line)">
    @if($hasMember)
        <label class="check" style="padding:8px 14px"><input type="checkbox" name="mine" value="1" data-autosubmit @checked(!empty($filters['mine']))> My tasks only</label>
    @endif
    <select name="assignee" data-autosubmit><option value="">All members</option>
        @foreach($members as $m)<option value="{{ $m->id }}" @selected(($filters['assignee'] ?? '') == $m->id)>{{ $m->name }}</option>@endforeach
    </select>
    <select name="project" data-autosubmit><option value="">All projects</option>
        @foreach($projects as $p)<option value="{{ $p->id }}" @selected(($filters['project'] ?? '') == $p->id)>{{ $p->title }}</option>@endforeach
    </select>
    <select name="priority" data-autosubmit><option value="">Any priority</option>
        @foreach($priorities as $k => $v)<option value="{{ $k }}" @selected(($filters['priority'] ?? '') === $k)>{{ $v }}</option>@endforeach
    </select>
    @if(collect($filters)->filter()->isNotEmpty())<a href="{{ route('admin.tasks.index') }}" class="btn sm ghost">Clear</a>@endif
</form>

@php $laneColors = ['todo' => '#6b7799', 'in_progress' => '#0db5bc', 'review' => '#f28a2a', 'done' => '#34d399']; @endphp
<div class="board" id="board" data-move-url="{{ url('admin/tasks') }}">
@foreach($columns as $status => $label)
    @php $items = $tasks->get($status, collect()); @endphp
    <section class="lane" data-status="{{ $status }}">
        <div class="lane-h"><span class="dot" style="background:{{ $laneColors[$status] }}"></span> {{ $label }} <span class="n">{{ $items->count() }}</span></div>
        <div class="lane-body">
            @foreach($items as $t)
                <article class="tcard" draggable="true" data-id="{{ $t->id }}">
                    <span class="prio {{ $t->priority }}"></span>
                    <a class="edit" href="{{ route('admin.tasks.edit', $t->id) }}" title="Edit"><x-icon name="edit" :size="15" /></a>
                    @if($t->project)<span class="proj">{{ $t->project->title }}</span>@endif
                    <h4>{{ $t->title }}</h4>
                    <div class="meta">
                        <span class="badge {{ badge_color($t->priority) }}" style="padding:2px 8px">{{ ucfirst($t->priority) }}</span>
                        @if($t->due_date)<span class="{{ $t->is_overdue ? 'late' : '' }}"><x-icon name="calendar" :size="12" /> {{ $t->due_date->format('d M') }}</span>@endif
                    </div>
                    <div class="foot">
                        <span class="meta">{{ $t->assignee?->name ?? 'Unassigned' }}</span>
                        @if($t->assignee)<span class="avatar" title="{{ $t->assignee->name }}">{{ $t->assignee->initials }}</span>@endif
                    </div>
                </article>
            @endforeach
        </div>
    </section>
@endforeach
</div>
@endsection
