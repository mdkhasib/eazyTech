@php
    $name = $f['name'];
    $type = $f['type'] ?? 'text';
    $col = 'c' . ($f['col'] ?? 12);
    $readonly = ! empty($f['readonly']);
    $default = $f['default'] ?? null;
    $current = old($name, $record ? $record->{$name} : $default);
    if ($type === 'tags' && is_array($current)) { $current = implode(', ', $current); }
    if ($type === 'date' && $current instanceof \Carbon\CarbonInterface) { $current = $current->format('Y-m-d'); }
    if ($type === 'date' && is_string($current) && strlen($current) > 10) { $current = substr($current, 0, 10); }
    if ($type === 'password') { $current = ''; }
    $err = $errors->first($name);
@endphp
<div class="field {{ $col }} {{ $err ? 'has-err' : '' }}">
    @if($type === 'checkbox')
        <label class="check">
            <input type="checkbox" name="{{ $name }}" value="1" @checked((bool) $current) @disabled($readonly)> {{ $f['label'] }}
        </label>
    @elseif($type === 'permissions')
        @php $selected = (array) old($name, $record?->permissions ?? []); @endphp
        <label>{{ $f['label'] }}</label>
        <div class="perm-grid">
            <label class="check" style="grid-column:1/-1"><input type="checkbox" name="{{ $name }}[]" value="*" @checked(in_array('*', $selected, true))> <span><b>Full access</b> — everything, including users &amp; roles</span></label>
            @foreach(\App\Models\Role::MODULES as $key => $label)
                <label class="check"><input type="checkbox" name="{{ $name }}[]" value="{{ $key }}" @checked(in_array($key, $selected, true))> {{ $label }}</label>
            @endforeach
        </div>
    @else
        <label for="f-{{ $name }}">{{ $f['label'] }}</label>
        @switch($type)
            @case('textarea')
                <textarea id="f-{{ $name }}" name="{{ $name }}" rows="{{ $f['rows'] ?? 4 }}" @readonly($readonly) @disabled($readonly) placeholder="{{ $f['placeholder'] ?? '' }}">{{ $current }}</textarea>
                @break
            @case('select')
                <select id="f-{{ $name }}" name="{{ $name }}" @disabled($readonly)>
                    @if(empty($f['required']))<option value="">— None —</option>@endif
                    @foreach($f['options'] as $val => $label)
                        <option value="{{ $val }}" @selected((string) $current === (string) $val)>{{ $label }}</option>
                    @endforeach
                </select>
                @break
            @case('image')
                @if($record && $record->{$name})
                    <div class="preview"><img src="{{ asset($record->{$name}) }}" alt="">
                        <label class="check" style="padding:7px 12px"><input type="checkbox" name="{{ $name }}_remove" value="1"> Remove</label></div>
                @endif
                <input id="f-{{ $name }}" type="file" name="{{ $name }}" accept="image/*">
                @break
            @case('permissions') @break
            @default
                <input id="f-{{ $name }}"
                       type="{{ in_array($type, ['tags']) ? 'text' : $type }}"
                       name="{{ $name }}" value="{{ $current }}"
                       @if(isset($f['step'])) step="{{ $f['step'] }}" @endif
                       @readonly($readonly) @disabled($readonly)
                       placeholder="{{ $f['placeholder'] ?? '' }}"
                       @if($type === 'password') autocomplete="new-password" @endif>
        @endswitch
    @endif
    @if(! empty($f['help']))<span class="help">{{ $f['help'] }}</span>@endif
    @if($err)<span class="err">{{ $err }}</span>@endif
</div>
