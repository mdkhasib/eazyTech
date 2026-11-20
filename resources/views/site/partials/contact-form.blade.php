@php $serviceList = $formServices ?? \App\Models\Service::active()->pluck('title'); @endphp
<form class="form-card" id="contact-form" method="post" action="{{ route('site.contact.send') }}" novalidate>
    @csrf
    <h3>{{ $heading ?? 'Tell us about your project' }}</h3>
    @if(session('success'))<div class="alert"><x-icon name="check" :size="20" /> {{ session('success') }}</div>@endif
    <div class="fgrid">
        <div class="fld"><label for="c-name">Your name *</label><input id="c-name" name="name" value="{{ old('name') }}" required autocomplete="name" placeholder="Jane Cooper">@error('name')<span class="err">{{ $message }}</span>@enderror</div>
        <div class="fld"><label for="c-email">Email *</label><input id="c-email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="you@company.com">@error('email')<span class="err">{{ $message }}</span>@enderror</div>
        <div class="fld"><label for="c-phone">Phone / WhatsApp</label><input id="c-phone" name="phone" value="{{ old('phone') }}" autocomplete="tel" placeholder="+1 555 000 000"></div>
        <div class="fld"><label for="c-budget">Budget</label>
            <select id="c-budget" name="budget">
                <option value="">Select a range</option>
                @foreach(['< $1,000', '$1,000 – $5,000', '$5,000 – $15,000', '$15,000+', 'Not sure yet'] as $b)<option @selected(old('budget') === $b)>{{ $b }}</option>@endforeach
            </select></div>
        <div class="fld full"><label for="c-service">I'm interested in</label>
            <select id="c-service" name="service">
                <option value="">Select a service</option>
                @foreach($serviceList as $s)<option @selected(old('service', $preselect ?? '') === $s)>{{ $s }}</option>@endforeach
            </select></div>
        <div class="fld full"><label for="c-msg">Project details *</label><textarea id="c-msg" name="message" required placeholder="What do you want to build or grow? Share goals, timeline, anything helpful…">{{ old('message') }}</textarea>@error('message')<span class="err">{{ $message }}</span>@enderror</div>
        <div class="hp" aria-hidden="true"><label>Leave this empty<input name="website" tabindex="-1" autocomplete="off"></label></div>
        <div class="full"><button class="btn primary block magnetic">Send message <x-icon name="send" :size="18" /></button></div>
    </div>
</form>
