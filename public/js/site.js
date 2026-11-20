(function () {
  'use strict';
  var $ = function (s, c) { return (c || document).querySelector(s); };
  var $$ = function (s, c) { return Array.prototype.slice.call((c || document).querySelectorAll(s)); };
  var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var fine = window.matchMedia && window.matchMedia('(pointer: fine)').matches;

  /* preloader */
  var loader = $('#loader');
  function hideLoader() { if (!loader || loader.classList.contains('done')) return; loader.classList.add('done'); setTimeout(function () { loader.style.display = 'none'; }, 900); }
  window.addEventListener('load', function () { setTimeout(hideLoader, 250); });
  setTimeout(hideLoader, 1600);

  /* nav + progress + back-to-top */
  var nav = $('#nav'), bar = $('#progress'), totop = $('#totop');
  function onScroll() {
    var y = window.pageYOffset || document.documentElement.scrollTop;
    var h = document.documentElement.scrollHeight - window.innerHeight;
    if (nav) nav.classList.toggle('scrolled', y > 24);
    if (bar) bar.style.width = (h > 0 ? (y / h) * 100 : 0) + '%';
    if (totop) totop.classList.toggle('show', y > 700);
  }
  window.addEventListener('scroll', onScroll, { passive: true }); onScroll();
  if (totop) totop.addEventListener('click', function () { window.scrollTo({ top: 0, behavior: 'smooth' }); });

  /* mobile menu */
  var mm = $('#mmenu');
  if (mm) {
    $('#burger').addEventListener('click', function () { mm.classList.add('open'); document.body.style.overflow = 'hidden'; });
    function closeMenu() { mm.classList.remove('open'); document.body.style.overflow = ''; }
    $('#mclose').addEventListener('click', closeMenu);
    $$('a', mm).forEach(function (a) { a.addEventListener('click', closeMenu); });
  }

  /* reveal on scroll */
  var revealEls = $$('.reveal');
  if ('IntersectionObserver' in window && !reduce) {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (e) { if (e.isIntersecting) { e.target.classList.add('in'); io.unobserve(e.target); } });
    }, { threshold: 0.12, rootMargin: '0px 0px -6% 0px' });
    revealEls.forEach(function (el) { io.observe(el); });
  } else { revealEls.forEach(function (el) { el.classList.add('in'); }); }

  /* counters */
  function runCounter(el) {
    var end = parseFloat(el.getAttribute('data-count')) || 0, t0 = null, dur = 1800;
    function step(t) { if (!t0) t0 = t; var p = Math.min(1, (t - t0) / dur); el.textContent = Math.round(end * (1 - Math.pow(1 - p, 4))); if (p < 1) requestAnimationFrame(step); }
    requestAnimationFrame(step);
  }
  var counters = $$('[data-count]');
  if ('IntersectionObserver' in window) {
    var cio = new IntersectionObserver(function (es) { es.forEach(function (e) { if (e.isIntersecting) { runCounter(e.target); cio.unobserve(e.target); } }); }, { threshold: 0.5 });
    counters.forEach(function (c) { cio.observe(c); });
  } else counters.forEach(function (c) { c.textContent = c.getAttribute('data-count'); });

  /* process line */
  var proc = $('[data-process]');
  if (proc && 'IntersectionObserver' in window) {
    var pio = new IntersectionObserver(function (es) { es.forEach(function (e) { if (e.isIntersecting) { proc.classList.add('in'); pio.disconnect(); } }); }, { threshold: 0.35 });
    pio.observe(proc);
  } else if (proc) proc.classList.add('in');

  /* typed words */
  var typed = $('#typed');
  if (typed) {
    var words = []; try { words = JSON.parse(typed.getAttribute('data-words')); } catch (e) {}
    if (reduce || !words.length) { typed.textContent = words[0] || ''; }
    else {
      var wi = 0, ci = 0, del = false;
      (function tick() {
        var w = words[wi];
        typed.textContent = w.slice(0, ci);
        var delay = del ? 35 : 75;
        if (!del && ci === w.length) { del = true; delay = 1500; }
        else if (del && ci === 0) { del = false; wi = (wi + 1) % words.length; delay = 350; }
        else ci += del ? -1 : 1;
        setTimeout(tick, delay);
      })();
    }
  }

  /* spotlight cards (mouse-follow glow) */
  $$('.spot').forEach(function (el) {
    el.addEventListener('pointermove', function (e) {
      var r = el.getBoundingClientRect();
      el.style.setProperty('--mx', (e.clientX - r.left) + 'px'); el.style.setProperty('--my', (e.clientY - r.top) + 'px');
    });
  });

  /* cursor glow + magnetic buttons */
  var glow = $('#glow');
  if (glow && fine && !reduce) {
    window.addEventListener('pointermove', function (e) { glow.style.opacity = 1; glow.style.transform = 'translate(' + (e.clientX - 260) + 'px,' + (e.clientY - 260) + 'px)'; }, { passive: true });
    $$('.magnetic').forEach(function (b) {
      b.addEventListener('pointermove', function (e) {
        var r = b.getBoundingClientRect(), x = (e.clientX - r.left - r.width / 2) * .22, y = (e.clientY - r.top - r.height / 2) * .35;
        b.style.transform = 'translate(' + x + 'px,' + y + 'px)';
      });
      b.addEventListener('pointerleave', function () { b.style.transform = ''; });
    });
  }

  /* filters (services / portfolio) */
  $$('[data-filter-group]').forEach(function (group) {
    var items = $$('[data-cat]');
    $$('[data-filter]', group).forEach(function (btn) {
      btn.addEventListener('click', function () {
        $$('[data-filter]', group).forEach(function (b) { b.classList.remove('on'); }); btn.classList.add('on');
        var f = btn.getAttribute('data-filter');
        items.forEach(function (it) {
          var show = f === 'all' || it.getAttribute('data-cat') === f;
          it.classList.toggle('hide', !show);
          if (show) { it.classList.add('in'); }
        });
      });
    });
  });

  /* tabs (solutions showcase) */
  $$('[data-tabs]').forEach(function (root) {
    var tabs = $$('.sol-tab', root), panes = $$('.sol-pane', root), url = $('#win-url', root);
    function open(id) {
      tabs.forEach(function (t) { t.classList.toggle('on', t.dataset.tab === id); });
      panes.forEach(function (p) { var on = p.dataset.pane === id; p.classList.toggle('on', on); if (on && url) url.textContent = p.dataset.url; });
    }
    tabs.forEach(function (t) { t.addEventListener('click', function () { open(t.dataset.tab); auto = false; }); });
    var auto = !reduce, idx = 0;
    if (auto) setInterval(function () { if (!auto || document.hidden) return; idx = (idx + 1) % tabs.length; open(tabs[idx].dataset.tab); }, 6500);
  });

  /* testimonials slider */
  var tr = $('#tst');
  if (tr) {
    var step = function () { var c = tr.firstElementChild; return c ? c.getBoundingClientRect().width + 22 : 400; };
    $('#tnext').addEventListener('click', function () { tr.scrollBy({ left: step(), behavior: 'smooth' }); });
    $('#tprev').addEventListener('click', function () { tr.scrollBy({ left: -step(), behavior: 'smooth' }); });
    var paused = false;
    tr.addEventListener('pointerenter', function () { paused = true; }); tr.addEventListener('pointerleave', function () { paused = false; });
    if (!reduce) setInterval(function () {
      if (paused || document.hidden) return;
      if (tr.scrollLeft + tr.clientWidth >= tr.scrollWidth - 8) tr.scrollTo({ left: 0, behavior: 'smooth' }); else tr.scrollBy({ left: step(), behavior: 'smooth' });
    }, 5500);
  }

  /* FAQ accordion */
  $$('.faq-q').forEach(function (q) {
    q.addEventListener('click', function () { var item = q.parentElement; var open = item.classList.contains('open'); $$('.faq-item.open').forEach(function (i) { i.classList.remove('open'); }); if (!open) item.classList.add('open'); });
  });

  /* scroll to form after submit / validation */
  var cf = $('#contact-form');
  if (cf && ($('.alert', cf) || $('.err', cf))) { setTimeout(function () { cf.scrollIntoView({ behavior: 'smooth', block: 'center' }); }, 350); }

  /* hero particle network */
  var cv = $('#net');
  if (cv && !reduce) {
    var ctx = cv.getContext('2d'), W = 0, H = 0, dpr = Math.min(window.devicePixelRatio || 1, 2), pts = [], mouse = { x: -999, y: -999 }, running = true;
    var colors = ['242,138,42', '236,90,94', '13,181,188', '242,214,36', '139,124,255'];
    function size() {
      var r = cv.getBoundingClientRect(); W = r.width; H = r.height; cv.width = W * dpr; cv.height = H * dpr; ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
      var n = Math.round(Math.min(110, Math.max(40, (W * H) / 15000)));
      pts = []; for (var i = 0; i < n; i++) pts.push({ x: Math.random() * W, y: Math.random() * H, vx: (Math.random() - .5) * .45, vy: (Math.random() - .5) * .45, r: Math.random() * 1.8 + .6, c: colors[(Math.random() * colors.length) | 0] });
    }
    size(); window.addEventListener('resize', size);
    cv.parentElement.addEventListener('pointermove', function (e) { var r = cv.getBoundingClientRect(); mouse.x = e.clientX - r.left; mouse.y = e.clientY - r.top; });
    cv.parentElement.addEventListener('pointerleave', function () { mouse.x = mouse.y = -999; });
    if ('IntersectionObserver' in window) new IntersectionObserver(function (es) { running = es[0].isIntersecting; if (running) draw(); }).observe(cv);
    function draw() {
      if (!running) return;
      ctx.clearRect(0, 0, W, H);
      var maxD = 130;
      for (var i = 0; i < pts.length; i++) {
        var p = pts[i];
        p.x += p.vx; p.y += p.vy;
        if (p.x < 0 || p.x > W) p.vx *= -1; if (p.y < 0 || p.y > H) p.vy *= -1;
        var dx = p.x - mouse.x, dy = p.y - mouse.y, md = Math.sqrt(dx * dx + dy * dy);
        if (md < 140) { p.x += dx / md * 1.2; p.y += dy / md * 1.2; }
        ctx.beginPath(); ctx.arc(p.x, p.y, p.r, 0, 6.2832); ctx.fillStyle = 'rgba(' + p.c + ',.85)'; ctx.fill();
        for (var j = i + 1; j < pts.length; j++) {
          var q = pts[j], ax = p.x - q.x, ay = p.y - q.y, d = ax * ax + ay * ay;
          if (d < maxD * maxD) { ctx.strokeStyle = 'rgba(' + p.c + ',' + (0.22 * (1 - Math.sqrt(d) / maxD)) + ')'; ctx.lineWidth = .8; ctx.beginPath(); ctx.moveTo(p.x, p.y); ctx.lineTo(q.x, q.y); ctx.stroke(); }
        }
        if (md < 170) { ctx.strokeStyle = 'rgba(255,255,255,' + (0.28 * (1 - md / 170)) + ')'; ctx.beginPath(); ctx.moveTo(p.x, p.y); ctx.lineTo(mouse.x, mouse.y); ctx.stroke(); }
      }
      requestAnimationFrame(draw);
    }
    draw();
  }

  /* hero parallax tilt on visual */
  var hv = $('.hero-visual');
  if (hv && fine && !reduce) {
    var hero = $('.hero');
    hero.addEventListener('pointermove', function (e) {
      var r = hero.getBoundingClientRect(), x = (e.clientX - r.left) / r.width - .5, y = (e.clientY - r.top) / r.height - .5;
      hv.style.transform = 'perspective(1200px) rotateY(' + (x * 8) + 'deg) rotateX(' + (-y * 8) + 'deg)';
    });
    hero.addEventListener('pointerleave', function () { hv.style.transform = ''; });
    hv.style.transition = 'transform .25s ease-out';
  }
})();
