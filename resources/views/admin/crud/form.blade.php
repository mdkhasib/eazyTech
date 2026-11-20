@extends('admin.layout')
@section('title', ($record ? 'Edit ' : 'New ') . strtolower($meta['singular']))

@section('content')
<div class="page-head">
    <div>
        <h2>{{ $record ? 'Edit' : 'New' }} {{ strtolower($meta['singular']) }}</h2>
        <p>{{ $meta['subtitle'] ?? '' }}</p>
    </div>
    <a href="{{ route($meta['route'] . '.index') }}" class="btn ghost">← Back to {{ strtolower($meta['title']) }}</a>
</div>

<form method="post" enctype="multipart/form-data" class="card"
      action="{{ $record ? route($meta['route'] . '.update', $record->getKey()) : route($meta['route'] . '.store') }}">
    @csrf
    @if($record) @method('PUT') @endif
    <div class="card-b">
        <div class="form-grid">
            @foreach($fields as $f)
                @include('admin.crud.field', ['f' => $f, 'record' => $record])
            @endforeach
            <div class="form-actions">
                @if(! (isset($meta['can_create']) && $meta['can_create'] === false && ! $record))
                <button class="btn primary"><x-icon name="check" :size="17" /> {{ $record ? 'Save changes' : 'Create' }}</button>
                @endif
                <a href="{{ route($meta['route'] . '.index') }}" class="btn ghost">Cancel</a>
            </div>
        </div>
    </div>
</form>
@endsection
