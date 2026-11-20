@extends('site.layout')
@section('title', 'Careers — Join the team · ' . site('site_name'))
@section('description', 'Permanent roles and remote part-time opportunities at EazyTech Solution.')

@section('content')
@include('site.partials.page-hero', [
    'crumbs' => ['Careers' => null],
    'eyebrow' => 'We are hiring',
    'title' => 'Build the future with <span class="grad">EazyTech.</span>',
    'lead' => 'Join as a permanent team member or work with us remotely on flexible part-time hours. Great people, interesting problems, transparent pay.',
])

<section class="sec">
    <div class="wrap">
        <div class="model" style="margin-bottom:80px">
            <div class="pm reveal left"><span class="pill">Permanent</span><h3>Grow your career with us</h3><p>Monthly salary, learning budget, paid leave and a clear growth path.</p></div>
            <div class="pt reveal right"><span class="pill">Remote part-time</span><h3>Flexible &amp; paid by the hour</h3><p>Choose your hours, log them in our timesheet, get paid for every approved hour.</p></div>
        </div>

        <div class="block-title reveal"><h2>Open <span class="grad">positions</span></h2></div>
        <div class="open-list">
            @forelse($openings as $j)
                <div class="job reveal">
                    <div>
                        <h3>{{ $j->title }}</h3>
                        <div class="meta">
                            <span class="{{ $j->employment_type === 'remote_part_time' ? 'pt' : 'pm' }}"><x-icon name="clock" :size="14" /> {{ \App\Models\TeamMember::TYPES[$j->employment_type] ?? $j->employment_type }}</span>
                            <span><x-icon name="map-pin" :size="14" /> {{ $j->location }}</span>
                            @if($j->department)<span><x-icon name="layers" :size="14" /> {{ $j->department }}</span>@endif
                        </div>
                        <p>{{ $j->description }}</p>
                        @if($j->requirements)<ul>@foreach(preg_split('/\r\n|\r|\n/', $j->requirements) as $line)@if(trim($line))<li>{{ $line }}</li>@endif @endforeach</ul>@endif
                    </div>
                    <a href="#apply" class="btn primary sm apply-btn" data-job="{{ $j->id }}">Apply now <x-icon name="arrow-right" :size="16" /></a>
                </div>
            @empty
                <p class="empty-note">No open positions right now — but we love hearing from talented people. Send an open application below.</p>
            @endforelse
        </div>
    </div>
</section>

<section class="sec tight" id="apply" style="background:var(--bg-2)">
    <div class="wrap" style="max-width:860px">
        <div class="sec-head center reveal"><span class="eyebrow">Apply</span><h2>Send us your <span class="grad">application</span></h2></div>
        <form class="form-card reveal" method="post" action="{{ route('site.apply') }}" id="apply-form">
            @csrf
            @if(session('success'))<div class="alert"><x-icon name="check" :size="20" /> {{ session('success') }}</div>@endif
            <div class="fgrid">
                <div class="fld full"><label for="a-job">Position</label>
                    <select id="a-job" name="job_opening_id"><option value="">Open application</option>
                        @foreach($openings as $j)<option value="{{ $j->id }}" @selected(old('job_opening_id') == $j->id)>{{ $j->title }}</option>@endforeach</select></div>
                <div class="fld"><label for="a-name">Full name *</label><input id="a-name" name="name" value="{{ old('name') }}" required>@error('name')<span class="err">{{ $message }}</span>@enderror</div>
                <div class="fld"><label for="a-email">Email *</label><input id="a-email" type="email" name="email" value="{{ old('email') }}" required>@error('email')<span class="err">{{ $message }}</span>@enderror</div>
                <div class="fld"><label for="a-phone">Phone / WhatsApp</label><input id="a-phone" name="phone" value="{{ old('phone') }}"></div>
                <div class="fld"><label for="a-url">Portfolio / CV / LinkedIn link</label><input id="a-url" type="url" name="portfolio_url" value="{{ old('portfolio_url') }}" placeholder="https://">@error('portfolio_url')<span class="err">{{ $message }}</span>@enderror</div>
                <div class="fld full"><label for="a-cl">Tell us about yourself</label><textarea id="a-cl" name="cover_letter" placeholder="Experience, availability (hours per week), expected pay…">{{ old('cover_letter') }}</textarea></div>
                <div class="hp" aria-hidden="true"><input name="website" tabindex="-1" autocomplete="off"></div>
                <div class="full"><button class="btn primary block magnetic">Submit application <x-icon name="send" :size="18" /></button></div>
            </div>
        </form>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.apply-btn').forEach(function (b) {
    b.addEventListener('click', function () { var s = document.getElementById('a-job'); if (s) s.value = b.dataset.job; });
});
@if(session('success') || $errors->any())
setTimeout(function () { var f = document.getElementById('apply-form'); if (f) f.scrollIntoView({ behavior: 'smooth', block: 'center' }); }, 350);
@endif
</script>
@endpush
