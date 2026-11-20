<article class="member reveal" style="--d:{{ ($i % 4) * .1 }}s">
    <div class="ph-av">@if($m->photo)<img src="{{ asset($m->photo) }}" alt="{{ $m->name }}">@else<span>{{ $m->initials }}</span>@endif</div>
    <h3>{{ $m->name }}</h3>
    <div class="role">{{ $m->designation }}</div>
    @if($m->bio)<p>{{ \Illuminate\Support\Str::limit($m->bio, 90) }}</p>@endif
    @if(! empty($m->skills))<div class="skills">@foreach(array_slice($m->skills, 0, 3) as $sk)<span>{{ $sk }}</span>@endforeach</div>@endif
    <span class="badge {{ $m->employment_type === 'remote_part_time' ? 'pt' : ($m->employment_type === 'permanent' ? 'pm' : '') }}">{{ $m->type_label }}{{ $m->work_mode === 'remote' ? ' · Remote' : '' }}</span>
</article>
