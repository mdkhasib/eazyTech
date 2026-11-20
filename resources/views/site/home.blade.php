@extends('site.layout')

@section('content')
{{-- ═════════ HERO ═════════ --}}
<section class="hero" id="top">
    <canvas id="net"></canvas>
    <span class="orb o1"></span><span class="orb o2"></span><span class="orb o3"></span>
    <div class="gridlines"></div>
    <div class="wrap hero-grid">
        <div>
            <span class="eyebrow reveal"><x-icon name="sparkles" :size="14" /> AI-powered software &amp; growth studio</span>
            <h1>
                <span class="line"><span>Plan it.</span></span>
                <span class="line"><span>Build it.</span></span>
                <span class="line"><span class="grad">Grow it.</span></span>
            </h1>
            <p class="lead reveal" style="--d:.5s">EazyTech Solution turns ideas into high-performance web apps, mobile apps, ERP and e-commerce platforms — then fuels them with Meta ads, social media and AI video marketing.</p>
            <div class="hero-cta reveal" style="--d:.65s">
                <a href="#contact" class="btn primary magnetic">Start your project <x-icon name="arrow-right" :size="19" /></a>
                <a href="{{ route('site.portfolio') }}" class="btn magnetic"><x-icon name="play" :size="16" /> See our work</a>
            </div>
            <div class="typed-line reveal" style="--d:.8s"><b>We build</b> <span>→</span> <span id="typed" data-words='["Web Applications","Mobile Apps","ERP & Accounting","Pharmacy & Shop Software","AI Video Ads","Meta Ads Campaigns","E-commerce Stores"]'></span><i class="caret"></i></div>
            <div class="hero-proof reveal" style="--d:.95s">
                <div><b><span data-count="{{ (int) site('stat_projects', 120) }}">0</span>+</b><span>Projects delivered</span></div>
                <div><b><span data-count="{{ (int) site('stat_clients', 60) }}">0</span>+</b><span>Happy clients</span></div>
                <div><b><span data-count="{{ (int) site('stat_years', 5) }}">0</span>+</b><span>Years of craft</span></div>
            </div>
        </div>

        <div class="hero-visual reveal zoom" style="--d:.3s" aria-hidden="true">
            <span class="ring r3"></span>
            <span class="ring r2">
                <span class="node" style="--c:var(--coral);left:93.3%;top:25%"><x-icon name="megaphone" :size="24" /></span>
                <span class="node" style="--c:var(--yellow);left:50%;top:100%"><x-icon name="video" :size="24" /></span>
                <span class="node" style="--c:var(--violet);left:6.7%;top:25%"><x-icon name="cpu" :size="24" /></span>
            </span>
            <span class="ring r1">
                <span class="node" style="--c:var(--teal);left:50%;top:0"><x-icon name="code" :size="24" /></span>
                <span class="node" style="--c:var(--orange);left:93.3%;top:75%"><x-icon name="phone" :size="24" /></span>
                <span class="node" style="--c:var(--mint);left:6.7%;top:75%"><x-icon name="cart" :size="24" /></span>
            </span>
            <div class="core"><img src="{{ asset('images/logo-icon.png') }}" alt=""></div>

            <div class="fcard code"><div class="dots"><i></i><i></i><i></i></div>
<pre style="margin:0;font:inherit"><span class="c">// ship faster</span>
<span class="k">const</span> growth = <span class="k">await</span>
  eazy.<span class="f">build</span>({
    web: <span class="n">true</span>, app: <span class="n">true</span>,
    ai: <span class="s">'on'</span>
  });</pre></div>
            <div class="fcard stat">
                <small>Revenue growth · 90 days</small><b class="grad-2">+248%</b>
                <svg viewBox="0 0 200 46" preserveAspectRatio="none"><defs><linearGradient id="sg" x1="0" x2="1"><stop offset="0" stop-color="#f28a2a"/><stop offset=".5" stop-color="#ec5a5e"/><stop offset="1" stop-color="#0db5bc"/></linearGradient></defs>
                    <polyline class="spark" points="0,40 22,34 44,38 66,26 88,29 110,18 132,22 154,10 176,13 200,3"/></svg>
            </div>
            <div class="fcard chip a"><span class="live"></span> AI video ad rendered · 12s</div>
            <div class="fcard chip b"><x-icon name="star" :size="16" style="color:var(--yellow)" /> 5.0 client rating</div>
        </div>
    </div>
    <div class="scroll-hint"><i></i> scroll</div>
</section>

{{-- ═════════ MARQUEE ═════════ --}}
@php
    $stack = [['Laravel', 'orange'], ['React', 'teal'], ['Flutter', 'teal'], ['Node.js', 'mint'], ['Vue.js', 'mint'], ['MySQL', 'orange'], ['Python', 'yellow'], ['AWS', 'orange'], ['Shopify', 'mint'], ['WordPress', 'teal'], ['Meta Ads', 'violet'], ['Google Ads', 'yellow'], ['OpenAI', 'coral'], ['Figma', 'coral'], ['Firebase', 'yellow'], ['Docker', 'teal']];
@endphp
<div class="marquee" aria-label="Technologies we work with">
    <div class="marquee-track">
        @for($i = 0; $i < 2; $i++)
            @foreach($stack as [$name, $color])<span style="--c:var(--{{ $color }})"><i></i>{{ $name }}</span>@endforeach
        @endfor
    </div>
</div>

{{-- ═════════ STATS ═════════ --}}
<section class="sec tight">
    <div class="wrap">
        <div class="stats reveal">
            <div><b class="grad"><span data-count="{{ (int) site('stat_projects', 120) }}">0</span>+</b><span>Projects delivered</span></div>
            <div><b class="grad"><span data-count="{{ (int) site('stat_clients', 60) }}">0</span>+</b><span>Clients worldwide</span></div>
            <div><b class="grad"><span data-count="{{ (int) site('stat_team', 25) }}">0</span>+</b><span>Specialists on the team</span></div>
            <div><b class="grad"><span data-count="98">0</span>%</b><span>Client satisfaction</span></div>
        </div>
    </div>
</section>

{{-- ═════════ SERVICES ═════════ --}}
<section class="sec bg-glow" id="services">
    <div class="wrap">
        <div class="sec-head reveal">
            <span class="eyebrow">What we do</span>
            <h2>Every digital solution your business needs — <span class="grad">under one roof.</span></h2>
            <p>From mission-critical software to marketing that moves the needle, our multidisciplinary team plans, builds and grows your business end to end.</p>
        </div>
        <div class="chips reveal" data-filter-group="svc">
            <button class="chip on" data-filter="all">All services</button>
            <button class="chip" data-filter="software">Software &amp; Apps</button>
            <button class="chip" data-filter="marketing">Marketing &amp; Social</button>
        </div>
        <div class="svc-grid">
            @foreach($services as $i => $s)
                <a href="{{ route('site.service', $s->slug) }}" class="svc spot reveal" data-cat="{{ $s->category }}" style="--d:{{ ($i % 4) * .08 }}s">
                    <span class="cat-tag">{{ $s->category === 'software' ? 'Software' : 'Growth' }}</span>
                    <span class="ib"><x-icon :name="$s->icon" :size="26" /></span>
                    <h3>{{ $s->title }}</h3>
                    <p>{{ $s->short_description }}</p>
                    <span class="more">Explore <x-icon name="arrow-right" :size="16" /></span>
                </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ═════════ SOLUTIONS SHOWCASE ═════════ --}}
<section class="sec bg-glow alt" id="solutions" style="background:var(--bg-2)">
    <div class="wrap">
        <div class="sec-head center reveal">
            <span class="eyebrow">Industry software</span>
            <h2>Ready-to-run systems for <span class="grad">shops, pharmacies &amp; growing teams</span></h2>
            <p>Battle-tested modules we customise to your workflow — live in weeks, not months.</p>
        </div>
        <div class="sol" data-tabs>
            <div class="sol-tabs reveal left">
                <button class="sol-tab on" data-tab="pos" style="--c:var(--teal)"><b><x-icon name="store" :size="22" /> Shop &amp; POS</b><span>Billing, barcode, stock &amp; dues</span></button>
                <button class="sol-tab" data-tab="pharma" style="--c:var(--mint)"><b><x-icon name="pill" :size="22" /> Pharmacy</b><span>Batch &amp; expiry tracking</span></button>
                <button class="sol-tab" data-tab="erp" style="--c:var(--orange)"><b><x-icon name="grid" :size="22" /> ERP &amp; Accounting</b><span>Sales, stock, ledger &amp; reports</span></button>
                <button class="sol-tab" data-tab="hr" style="--c:var(--coral)"><b><x-icon name="users" :size="22" /> HR &amp; Payroll</b><span>Permanent + remote part-time</span></button>
            </div>

            <div class="win reveal right">
                <div class="win-bar"><i></i><i></i><i></i><em id="win-url">pos.eazytech.app</em></div>

                <div class="sol-pane on" data-pane="pos" data-url="pos.eazytech.app">
                    <div class="m-row m-2">
                        <div class="m-card">
                            <div class="m-title"><span>Quick products</span><span>▮▯▮▮▯▮ scan</span></div>
                            <div class="pos-grid">
                                @foreach([['Rice 5kg', '$6.20', 'teal', 'store'], ['Olive Oil', '$9.90', 'orange', 'pill'], ['Milk 1L', '$1.60', 'coral', 'cart'], ['Tea Pack', '$3.40', 'yellow', 'store'], ['Soap ×3', '$2.80', 'mint', 'cart'], ['Noodles', '$0.90', 'violet', 'store']] as [$n, $p, $c, $ic])
                                    <div class="pos-item" style="--c:var(--{{ $c }})"><div class="ib2"><x-icon :name="$ic" :size="19" /></div><b>{{ $n }}</b><small>{{ $p }}</small></div>
                                @endforeach
                            </div>
                        </div>
                        <div class="m-card">
                            <div class="m-title"><span>Current sale</span><span>#INV-1042</span></div>
                            <div class="cart-line"><span>Rice 5kg × 2</span><b>$12.40</b></div>
                            <div class="cart-line"><span>Milk 1L × 3</span><b>$4.80</b></div>
                            <div class="cart-line"><span>Olive Oil × 1</span><b>$9.90</b></div>
                            <div class="cart-total"><span>Total</span><span class="grad">$27.10</span></div>
                            <div class="pay-btn">Pay · Cash / Card / bKash</div>
                        </div>
                    </div>
                </div>

                <div class="sol-pane" data-pane="pharma" data-url="pharmacy.eazytech.app">
                    <div class="m-row m-3" style="margin-bottom:14px">
                        <div class="m-card m-kpi"><small>Expiring in 30 days</small><b style="color:#ff7a7e">12</b><em>action needed</em></div>
                        <div class="m-card m-kpi"><small>Low stock items</small><b style="color:var(--yellow)">8</b><em>reorder suggested</em></div>
                        <div class="m-card m-kpi"><small>Today's sales</small><b>$1,284</b><em>▲ 14% vs yesterday</em></div>
                    </div>
                    <div class="m-card">
                        <table class="m-table">
                            <tr><th>Medicine</th><th>Batch</th><th>Expiry</th><th>Stock</th></tr>
                            <tr><td>Paracetamol 500mg</td><td>PC-2291</td><td><span class="tag g">Mar 2027</span></td><td>420</td></tr>
                            <tr><td>Amoxicillin 250mg</td><td>AM-1180</td><td><span class="tag r">12 days left</span></td><td>36</td></tr>
                            <tr><td>Omeprazole 20mg</td><td>OM-0457</td><td><span class="tag y">2 months</span></td><td>88</td></tr>
                            <tr><td>Cetirizine 10mg</td><td>CT-7731</td><td><span class="tag g">Aug 2027</span></td><td>260</td></tr>
                            <tr><td>Insulin Glargine</td><td>IN-3320</td><td><span class="tag b">Cold chain</span></td><td>14</td></tr>
                        </table>
                    </div>
                </div>

                <div class="sol-pane" data-pane="erp" data-url="erp.eazytech.app">
                    <div class="m-row m-3" style="margin-bottom:14px">
                        <div class="m-card m-kpi"><small>Revenue (MTD)</small><b>$84.2k</b><em>▲ 18.4%</em></div>
                        <div class="m-card m-kpi"><small>Receivables</small><b>$12.9k</b><em style="color:var(--yellow)">7 invoices due</em></div>
                        <div class="m-card m-kpi"><small>Net profit</small><b>$23.5k</b><em>▲ 9.1%</em></div>
                    </div>
                    <div class="m-row m-2">
                        <div class="m-card"><div class="m-title"><span>Sales — last 12 weeks</span></div>
                            <div class="m-bars">@foreach([34, 48, 41, 58, 52, 66, 61, 74, 69, 82, 78, 94] as $k => $h)<i style="height:{{ $h }}%;animation-delay:{{ $k * .05 }}s"></i>@endforeach</div></div>
                        <div class="m-card"><div class="m-title"><span>Latest invoices</span></div>
                            <div class="cart-line"><span>INV-2208 · Northwind</span><span class="tag g">Paid</span></div>
                            <div class="cart-line"><span>INV-2209 · FreshCart</span><span class="tag y">Due</span></div>
                            <div class="cart-line"><span>INV-2210 · UrbanNest</span><span class="tag g">Paid</span></div>
                            <div class="cart-line"><span>INV-2211 · Brightpath</span><span class="tag b">Draft</span></div></div>
                    </div>
                </div>

                <div class="sol-pane" data-pane="hr" data-url="hr.eazytech.app">
                    <div class="m-row m-2">
                        <div class="m-card">
                            <div class="m-title"><span>Team this month</span><span>payroll auto-calculated</span></div>
                            @foreach([['TH', 'Tanvir H.', 'Permanent', 'Fixed salary', 100, 'b'], ['IH', 'Imran H.', 'Remote part-time', '94h / 108h approved', 87, 'o'], ['FA', 'Farhana A.', 'Remote part-time', '81h / 104h approved', 78, 'o'], ['SK', 'Sadia K.', 'Permanent', 'Fixed salary', 100, 'b']] as [$in, $nm, $ty, $sub, $pct, $tg])
                                <div class="emp"><span class="av">{{ $in }}</span><div class="mid"><b>{{ $nm }}</b><small>{{ $sub }}</small><div class="mini-bar"><i style="width:{{ $pct }}%"></i></div></div><span class="tag {{ $tg }}">{{ $ty }}</span></div>
                            @endforeach
                        </div>
                        <div class="m-card m-kpi"><div class="m-title"><span>Payroll</span></div><small>This month</small><b class="grad">$14,820</b>
                            <div class="cart-line" style="margin-top:14px"><span>Salaried</span><b>$11,200</b></div><div class="cart-line"><span>Hourly (approved)</span><b>$3,620</b></div><div class="cart-line"><span>Pending leave</span><b>3</b></div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═════════ PROCESS ═════════ --}}
<section class="sec" id="process">
    <div class="wrap">
        <div class="sec-head center reveal">
            <span class="eyebrow">How we work</span>
            <h2>A simple, transparent path <span class="grad">from idea to impact</span></h2>
            <p>Agile sprints, weekly demos and zero surprises. You always know what is being built and why.</p>
        </div>
        <div class="process reveal" data-process>
            <span class="fill"></span>
            @foreach([['01', 'Discover', 'We dig into your goals, users and numbers to define scope and success.', 'target'], ['02', 'Design', 'UX flows and a premium UI you approve before a line of code is written.', 'palette'], ['03', 'Build', 'Two-week sprints, clean code, automated tests and a live demo every week.', 'code'], ['04', 'Launch', 'Secure deployment, training and a smooth go-live — we stay on call.', 'rocket'], ['05', 'Grow', 'Ads, SEO, social and AI video to bring users in — and keep improving.', 'trending']] as [$n, $t, $d, $ic])
                <div class="step"><div class="num"><x-icon :name="$ic" :size="30" /></div><div><small>STEP {{ $n }}</small><h3>{{ $t }}</h3><p>{{ $d }}</p></div></div>
            @endforeach
        </div>
    </div>
</section>

{{-- ═════════ GROWTH (META ADS + AI VIDEO) ═════════ --}}
<section class="sec growth" id="growth">
    <div class="wrap growth-grid">
        <div class="reveal left">
            <span class="eyebrow">Social &amp; performance marketing</span>
            <h2 style="font-size:clamp(34px,4.4vw,56px);margin:20px 0 18px">Videos that stop the scroll. <span class="grad">Ads that pay for themselves.</span></h2>
            <p class="muted" style="font-size:18px">Our AI video studio and Meta Ads specialists create, test and scale creatives faster than any traditional agency — with reporting you can actually read.</p>
            <ul class="tick-list">
                <li style="--c:var(--coral)"><span class="tick"><x-icon name="check" :size="15" /></span><span><b>AI video production</b>Product ads, explainers, reels and multi-language voice-overs in days.</span></li>
                <li style="--c:var(--orange)"><span class="tick"><x-icon name="check" :size="15" /></span><span><b>Meta Ads (Facebook &amp; Instagram)</b>Pixel &amp; Conversion API, creative testing and retargeting funnels.</span></li>
                <li style="--c:var(--teal)"><span class="tick"><x-icon name="check" :size="15" /></span><span><b>Social media management</b>Calendars, designs, captions and community — always on-brand.</span></li>
            </ul>
            <a href="{{ route('site.service', 'meta-ads') }}" class="btn primary magnetic">Grow with us <x-icon name="arrow-right" :size="18" /></a>
        </div>
        <div class="phones reveal right">
            <div class="growth-stat s2"><b class="grad-2">4.6×</b><small>Return on ad spend</small></div>
            @foreach([['p1', 'Summer launch 🔥', 'Shop now'], ['p2', 'Meet the future of retail', 'Learn more'], ['p3', 'Limited offer — 48h', 'Order now']] as [$cls, $cap, $cta])
                <div class="ph {{ $cls }}">
                    <span class="notch"></span><div class="vid"></div>
                    <div class="top-ui"><i></i> <span>eazytech.client</span></div><span class="spons">SPONSORED</span>
                    <div class="play"><x-icon name="play" :size="26" /></div>
                    <div class="rail"><div><span>♥</span>12k</div><div><span>💬</span>348</div><div><span>↗</span>1.2k</div></div>
                    <div class="cap"><b>{{ $cap }}</b>AI-generated video ad</div>
                </div>
            @endforeach
            <div class="growth-stat s1"><b>−52%</b><small>Cost per lead</small></div>
        </div>
    </div>
</section>

{{-- ═════════ PORTFOLIO ═════════ --}}
<section class="sec bg-glow" id="work">
    <div class="wrap">
        <div style="display:flex;justify-content:space-between;align-items:flex-end;gap:24px;flex-wrap:wrap;margin-bottom:56px">
            <div class="sec-head reveal" style="margin:0">
                <span class="eyebrow">Selected work</span>
                <h2>Products &amp; campaigns <span class="grad">we're proud of</span></h2>
            </div>
            <a href="{{ route('site.portfolio') }}" class="link-arrow reveal">View all projects <x-icon name="arrow-right" :size="18" /></a>
        </div>
        <div class="work-grid">
            @foreach($featured as $i => $item)
                <a href="{{ route('site.project', $item->slug) }}" class="work reveal" style="--d:{{ ($i % 3) * .1 }}s">
                    <div class="art-wrap">
                        @include('site.partials.art', ['item' => $item])
                        <span class="art-badge">{{ $item->category_label }}</span>
                        @if($item->metric_value)<span class="metric"><b class="grad-2">{{ $item->metric_value }}</b><small>{{ $item->metric_label }}</small></span>@endif
                    </div>
                    <div class="work-body">
                        <h3>{{ $item->title }}</h3>
                        <p>{{ $item->summary }}</p>
                        <div class="tags">@foreach(array_slice($item->tags ?? [], 0, 3) as $t)<span>{{ $t }}</span>@endforeach</div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ═════════ WHY ═════════ --}}
<section class="sec" style="background:var(--bg-2)">
    <div class="wrap">
        <div class="sec-head center reveal">
            <span class="eyebrow">Why EazyTech</span>
            <h2>Built like a <span class="grad">high-tech product studio</span></h2>
            <p>We combine senior engineering, sharp design and growth marketing in one accountable team.</p>
        </div>
        <div class="why">
            @foreach([['rocket', 'Ship in weeks', 'Agile sprints and reusable modules mean your MVP is live far sooner than the industry average.'], ['cpu', 'AI-first thinking', 'We weave automation and AI assistants into products and marketing to save you time and money.'], ['shield', 'Secure & scalable', 'OWASP-aware code, role-based access, backups and cloud-ready architecture from day one.'], ['dollar', 'Transparent pricing', 'Fixed-scope quotes, milestone billing and no hidden fees — you stay in control of the budget.'], ['users', 'Remote-first talent', 'A blend of permanent experts and flexible remote part-timers lets us scale up exactly when you need.'], ['activity', 'Support that stays', 'Post-launch maintenance, monitoring and growth retainers — we are your long-term tech partner.']] as $i => [$ic, $t, $d])
                <div class="reveal" data-n="0{{ $i + 1 }}" style="--d:{{ ($i % 3) * .1 }}s"><div class="ib"><x-icon :name="$ic" :size="26" /></div><h3>{{ $t }}</h3><p>{{ $d }}</p></div>
            @endforeach
        </div>
    </div>
</section>

{{-- ═════════ TESTIMONIALS ═════════ --}}
@if($testimonials->isNotEmpty())
<section class="sec" id="reviews">
    <div class="wrap">
        <div class="sec-head reveal">
            <span class="eyebrow">Client love</span>
            <h2>Don't take our word <span class="grad">for it</span></h2>
        </div>
        <div class="tst-wrap reveal">
            <div class="tst-track" id="tst">
                @foreach($testimonials as $t)
                    <figure class="tst" style="margin:0">
                        <div class="stars">{{ str_repeat('★', $t->rating) }}</div>
                        <blockquote>{{ $t->quote }}</blockquote>
                        <figcaption class="who"><span class="av">@if($t->photo)<img src="{{ asset($t->photo) }}" alt="{{ $t->name }}">@else{{ initials($t->name) }}@endif</span><span><b>{{ $t->name }}</b><small>{{ $t->position }}{{ $t->company ? ' · ' . $t->company : '' }}</small></span></figcaption>
                    </figure>
                @endforeach
            </div>
            <div class="slider-nav"><button id="tprev" aria-label="Previous"><x-icon name="arrow-right" :size="20" style="transform:rotate(180deg)" /></button><button id="tnext" aria-label="Next"><x-icon name="arrow-right" :size="20" /></button></div>
        </div>
    </div>
</section>
@endif

{{-- ═════════ TEAM ═════════ --}}
@if($team->isNotEmpty())
<section class="sec bg-glow alt" style="background:var(--bg-2)">
    <div class="wrap">
        <div style="display:flex;justify-content:space-between;align-items:flex-end;gap:24px;flex-wrap:wrap;margin-bottom:56px">
            <div class="sec-head reveal" style="margin:0"><span class="eyebrow">The people</span><h2>Meet the minds <span class="grad">behind the magic</span></h2></div>
            <a href="{{ route('site.about') }}" class="link-arrow reveal">Meet the whole team <x-icon name="arrow-right" :size="18" /></a>
        </div>
        <div class="team-grid">
            @foreach($team as $i => $m)
                @include('site.partials.member', ['m' => $m, 'i' => $i])
            @endforeach
        </div>
    </div>
</section>
@endif

@include('site.partials.cta')
@endsection
