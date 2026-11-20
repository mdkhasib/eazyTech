@extends('site.layout')
@section('title', 'Contact us · ' . site('site_name'))
@section('description', 'Tell us about your project — web, mobile, ERP, e-commerce or marketing. We reply within one business day.')

@section('content')
@include('site.partials.page-hero', [
    'crumbs' => ['Contact' => null],
    'eyebrow' => 'Contact',
    'title' => 'Let\'s talk about <span class="grad">your next project.</span>',
    'lead' => 'A quick message is all it takes. We will come back with questions, ideas and a clear quote.',
])

<section class="sec">
    <div class="wrap" style="display:grid;grid-template-columns:.9fr 1.1fr;gap:56px;align-items:start" id="contact-grid">
        <div class="info-cards reveal left">
            <a class="info-card" href="mailto:{{ site('email') }}" style="--c:var(--teal)"><span class="ib"><x-icon name="mail" :size="24" /></span><span><small>Email us</small><b>{{ site('email') }}</b></span></a>
            <a class="info-card" href="tel:{{ preg_replace('/[^\d+]/', '', (string) site('phone')) }}" style="--c:var(--orange)"><span class="ib"><x-icon name="call" :size="24" /></span><span><small>Call us</small><b>{{ site('phone') }}</b></span></a>
            @if(site('whatsapp'))<a class="info-card" href="https://wa.me/{{ preg_replace('/\D/', '', site('whatsapp')) }}" target="_blank" rel="noopener" style="--c:var(--mint)"><span class="ib"><x-icon name="message" :size="24" /></span><span><small>WhatsApp</small><b>Chat with us now</b></span></a>@endif
            <div class="info-card" style="--c:var(--coral)"><span class="ib"><x-icon name="map-pin" :size="24" /></span><span><small>Where we work</small><b style="font-size:16px">{{ site('address') }}</b></span></div>
            <div class="info-card" style="--c:var(--yellow)"><span class="ib"><x-icon name="clock" :size="24" /></span><span><small>Working hours</small><b>{{ site('working_hours') }}</b></span></div>
        </div>
        <div class="reveal right">@include('site.partials.contact-form', ['formServices' => $services, 'heading' => 'Send us a message'])</div>
    </div>
</section>

<section class="sec tight" style="background:var(--bg-2)">
    <div class="wrap">
        <div class="sec-head center reveal"><span class="eyebrow">FAQ</span><h2>Quick <span class="grad">answers</span></h2></div>
        <div class="faq reveal">
            @foreach([
                ['How long does a typical project take?', 'A marketing campaign can launch in a week. Websites and mobile MVPs usually take 4–8 weeks, while ERP, accounting or pharmacy systems range from 6–16 weeks depending on modules. You get a fixed timeline in your quote.'],
                ['How is pricing structured?', 'Fixed-scope quotes with milestone billing for software, and monthly retainers for marketing and social media. No hidden fees — you approve the scope before we start.'],
                ['Do you provide support after launch?', 'Yes. Every project includes a free support window, and we offer maintenance plans covering updates, monitoring, backups and new features.'],
                ['Can you customise ready-made software like POS or pharmacy systems?', 'Absolutely. Our shop, pharmacy, accounting and HR modules are starting points that we tailor to your workflow, branding and reports.'],
                ['Do you work with clients outside our own country?', 'Yes — our remote-first team works with clients across time zones and communicates over WhatsApp, email, Google Meet or Zoom.'],
            ] as [$q, $a])
                <div class="faq-item"><button class="faq-q">{{ $q }} <x-icon name="chevron-down" :size="22" /></button><div class="faq-a"><p>{{ $a }}</p></div></div>
            @endforeach
        </div>
    </div>
</section>
@endsection

@push('scripts')
<style>@media (max-width: 1000px) { #contact-grid { grid-template-columns: 1fr !important; } }</style>
@endpush
