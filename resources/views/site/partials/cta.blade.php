<section class="sec tight" id="contact">
    <div class="wrap">
        <div class="cta reveal zoom">
            <div class="gridlines"></div>
            <div class="cta-grid">
                <div>
                    <span class="eyebrow">Let's build something great</span>
                    <h2>Ready to <span class="grad">plan &amp; build</span> your next big thing?</h2>
                    <p class="lead">Tell us what you need — a web app, a mobile app, an ERP, an ad campaign or all of it. We reply within one business day with a clear plan and a transparent quote.</p>
                    <div class="contact-lines">
                        <a href="mailto:{{ site('email') }}"><span class="ib"><x-icon name="mail" :size="20" /></span><span>{{ site('email') }}</span></a>
                        <a href="tel:{{ preg_replace('/[^\d+]/', '', (string) site('phone')) }}"><span class="ib"><x-icon name="call" :size="20" /></span><span>{{ site('phone') }}</span></a>
                        <div><span class="ib"><x-icon name="clock" :size="20" /></span><span>{{ site('working_hours') }}</span></div>
                    </div>
                </div>
                @include('site.partials.contact-form', ['preselect' => $preselect ?? ''])
            </div>
        </div>
    </div>
</section>
