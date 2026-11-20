(function () {
  'use strict';
  var $ = function (s, c) { return (c || document).querySelector(s); };
  var $$ = function (s, c) { return Array.prototype.slice.call((c || document).querySelectorAll(s)); };
  var csrf = (document.querySelector('meta[name=csrf-token]') || {}).content;

  /* sidebar (mobile) */
  var side = $('#side'), scrim = $('#scrim');
  function toggleSide(open) { if (!side) return; side.classList.toggle('open', open); scrim.classList.toggle('on', open); }
  var burger = $('#burger');
  if (burger) burger.addEventListener('click', function () { toggleSide(!side.classList.contains('open')); });
  if (scrim) scrim.addEventListener('click', function () { toggleSide(false); });

  /* theme */
  var themeBtn = $('#theme');
  if (themeBtn) themeBtn.addEventListener('click', function () {
    var next = document.documentElement.getAttribute('data-theme') === 'light' ? 'dark' : 'light';
    document.documentElement.setAttribute('data-theme', next);
    try { localStorage.setItem('ez-admin-theme', next); } catch (e) {}
  });

  /* confirm destructive forms */
  $$('form[data-confirm]').forEach(function (f) {
    f.addEventListener('submit', function (e) { if (!window.confirm(f.dataset.confirm)) e.preventDefault(); });
  });

  /* auto-submit filters */
  $$('[data-autosubmit]').forEach(function (el) {
    el.addEventListener('change', function () { el.form.submit(); });
  });

  /* flash auto-hide */
  $$('.flash[data-autohide]').forEach(function (f) {
    setTimeout(function () { f.style.transition = 'opacity .5s'; f.style.opacity = 0; setTimeout(function () { f.remove(); }, 600); }, 4500);
  });

  /* animated bars + counters */
  requestAnimationFrame(function () {
    $$('.bars i[data-h]').forEach(function (b, i) { setTimeout(function () { b.style.height = Math.max(3, b.dataset.h) + '%'; }, 80 * i); });
  });
  $$('[data-count]').forEach(function (el) {
    var end = parseInt(el.dataset.count, 10) || 0, t0 = null;
    if (end === 0) return;
    function step(t) { if (!t0) t0 = t; var p = Math.min(1, (t - t0) / 900); el.textContent = Math.round(end * (1 - Math.pow(1 - p, 3))); if (p < 1) requestAnimationFrame(step); }
    requestAnimationFrame(step);
  });

  /* kanban drag & drop */
  var board = $('#board');
  if (board) {
    var dragged = null;
    $$('.tcard', board).forEach(function (card) {
      card.addEventListener('dragstart', function (e) { dragged = card; card.classList.add('dragging'); e.dataTransfer.effectAllowed = 'move'; try { e.dataTransfer.setData('text/plain', card.dataset.id); } catch (x) {} });
      card.addEventListener('dragend', function () { card.classList.remove('dragging'); $$('.lane', board).forEach(function (l) { l.classList.remove('over'); }); dragged = null; });
    });
    $$('.lane', board).forEach(function (lane) {
      var body = $('.lane-body', lane);
      lane.addEventListener('dragover', function (e) {
        e.preventDefault(); lane.classList.add('over');
        if (!dragged) return;
        var after = null, y = e.clientY;
        $$('.tcard:not(.dragging)', body).forEach(function (c) { var r = c.getBoundingClientRect(); if (y > r.top + r.height / 2) after = c; });
        if (after) after.after(dragged); else body.prepend(dragged);
      });
      lane.addEventListener('dragleave', function (e) { if (!lane.contains(e.relatedTarget)) lane.classList.remove('over'); });
      lane.addEventListener('drop', function (e) {
        e.preventDefault(); lane.classList.remove('over');
        if (!dragged) return;
        var id = dragged.dataset.id, status = lane.dataset.status;
        var order = $$('.tcard', body).map(function (c) { return parseInt(c.dataset.id, 10); });
        $$('.lane', board).forEach(function (l) { $('.n', l).textContent = $$('.tcard', l).length; });
        fetch(board.dataset.moveUrl + '/' + id + '/move', {
          method: 'PATCH',
          headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
          body: JSON.stringify({ status: status, order: order })
        }).then(function (r) { if (!r.ok) throw new Error(); }).catch(function () { alert('Could not save the move — refreshing.'); location.reload(); });
      });
    });
  }
})();
