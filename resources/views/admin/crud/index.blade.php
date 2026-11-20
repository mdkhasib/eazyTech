@extends('admin.layout')
@section('title', $meta['title'])

@section('content')
<div class="page-head">
    <div>
        <h2>{{ $meta['title'] }}</h2>
        <p>{{ $meta['subtitle'] ?? '' }}</p>
    </div>
    @if($meta['can_create'] ?? true)
        <a href="{{ route($meta['route'] . '.create') }}" class="btn primary"><x-icon name="plus" :size="17" /> New {{ strtolower($meta['singular']) }}</a>
    @endif
</div>

<div class="card">
    @if($searchable || count($filters))
    <form class="toolbar" method="get">
        @if($searchable)
            <div class="search"><x-icon name="search" :size="16" /><input type="search" name="q" value="{{ request('q') }}" placeholder="Search {{ strtolower($meta['title']) }}…"></div>
        @endif
        @foreach($filters as $f)
            <select name="{{ $f['name'] }}" data-autosubmit>
                <option value="">{{ $f['label'] }}</option>
                @foreach($f['options'] as $val => $label)
                    <option value="{{ $val }}" @selected((string) request($f['name']) === (string) $val)>{{ $label }}</option>
                @endforeach
            </select>
        @endforeach
        <button class="btn sm">Filter</button>
        @if(request()->hasAny(array_merge(['q'], array_column($filters, 'name'))) && collect(request()->only(array_merge(['q'], array_column($filters, 'name'))))->filter()->isNotEmpty())
            <a class="btn sm ghost" href="{{ route($meta['route'] . '.index') }}">Clear</a>
        @endif
    </form>
    @endif

    <div class="tbl-wrap">
    <table class="tbl">
        <thead><tr>
            @foreach($columns as $c)<th>{{ $c['label'] }}</th>@endforeach
            <th class="act">Actions</th>
        </tr></thead>
        <tbody>
        @forelse($rows as $row)
            <tr>
                @foreach($columns as $c)
                    <td>@include('admin.crud.cell', ['row' => $row, 'c' => $c])</td>
                @endforeach
                <td class="act">
                    @foreach($ctrl->actionsFor($row) as $a)
                        <form method="post" action="{{ $a['url'] }}">@csrf @method('PATCH')
                            @foreach(($a['data'] ?? []) as $k => $v)<input type="hidden" name="{{ $k }}" value="{{ $v }}">@endforeach
                            <button class="btn sm {{ $a['tone'] ?? 'ghost' }}">{{ $a['label'] }}</button>
                        </form>
                    @endforeach
                    <a href="{{ route($meta['route'] . '.edit', $row->getKey()) }}" class="btn sm icon" title="{{ ($meta['can_create'] ?? true) ? 'Edit' : 'View / update' }}"><x-icon name="{{ ($meta['can_create'] ?? true) ? 'edit' : 'eye' }}" :size="15" /></a>
                    <form method="post" action="{{ route($meta['route'] . '.destroy', $row->getKey()) }}" data-confirm="Delete this {{ strtolower($meta['singular']) }}? This cannot be undone.">@csrf @method('DELETE')
                        <button class="btn sm icon danger" title="Delete"><x-icon name="trash" :size="15" /></button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="{{ count($columns) + 1 }}"><div class="empty">Nothing here yet.</div></td></tr>
        @endforelse
        </tbody>
    </table>
    </div>

    @if($rows->hasPages() || $rows->total() > 0)
        <div class="pager">
            <span>Showing {{ $rows->firstItem() ?? 0 }}–{{ $rows->lastItem() ?? 0 }} of {{ $rows->total() }}</span>
            @if($rows->hasPages()) {{ $rows->links('admin.crud.pagination') }} @endif
        </div>
    @endif
</div>
@endsection
