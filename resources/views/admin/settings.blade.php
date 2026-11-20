@extends('admin.layout')
@section('title', 'Settings')

@section('content')
<div class="page-head"><div><h2>Site settings</h2><p>Contact details, social links and the numbers shown on the public website.</p></div></div>
<form method="post" action="{{ route('admin.settings.update') }}">
    @csrf @method('PUT')
    <div class="grid g-2e">
    @foreach($groups as $group => $fields)
        <div class="card"><div class="card-h"><h3>{{ $group }}</h3></div>
            <div class="card-b"><div class="form-grid">
                @foreach($fields as $key => [$label, $type])
                    <div class="field {{ $errors->has($key) ? 'has-err' : '' }}">
                        <label for="s-{{ $key }}">{{ $label }}</label>
                        <input id="s-{{ $key }}" type="{{ $type }}" name="{{ $key }}" value="{{ old($key, $values[$key] ?? '') }}">
                        @error($key)<span class="err">{{ $message }}</span>@enderror
                    </div>
                @endforeach
            </div></div>
        </div>
    @endforeach
    </div>
    <div style="margin-top:20px"><button class="btn primary"><x-icon name="check" :size="17" /> Save settings</button></div>
</form>
@endsection
