/* BWN PHP CMS — interaksi ringan: reveal, tabs, palette Ctrl+K, kalkulator, lacak */
(function () {
  'use strict';

  /* ---------- Reveal on scroll ---------- */
  var io = new IntersectionObserver(function (entries) {
    entries.forEach(function (en) {
      if (en.isIntersecting) { en.target.classList.add('in'); io.unobserve(en.target); }
    });
  }, { threshold: 0.12 });
  document.querySelectorAll('.reveal').forEach(function (el) { io.observe(el); });

  /* ---------- Tabs ---------- */
  document.querySelectorAll('.tab-btn[data-tab]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var group = btn.closest('[data-tabs]');
      if (!group) return;
      group.querySelectorAll('.tab-btn').forEach(function (b) { b.classList.toggle('active', b === btn); });
      group.querySelectorAll('.tab-panel').forEach(function (p) {
        p.classList.toggle('active', p.dataset.panel === btn.dataset.tab);
      });
    });
  });

  /* ---------- Command palette (Ctrl+K) ---------- */
  var INDEX = null, backdrop = document.getElementById('palBackdrop'),
      input = document.getElementById('palInput'), list = document.getElementById('palList');
  function loadIndex() {
    if (INDEX) return Promise.resolve(INDEX);
    var link = document.getElementById('mainStyle');
    var root = link ? link.href.split('?')[0].replace(/assets\/style\.css.*$/, '') : '/';
    return fetch(root + 'search-index.json').then(function (r) { return r.json(); });
  }
  function openPalette() {
    backdrop.classList.add('open');
    loadIndex().then(function (idx) {
      INDEX = idx;
      input.value = ''; render([]);
      input.focus();
    });
  }
  function closePalette() { backdrop.classList.remove('open'); }
  function render(items) {
    list.innerHTML = items.length === 0
      ? '<div style="padding:16px;color:#5b6b60;font-size:.9rem">Ketik minimal 2 huruf — contoh: bandung, ginjal, grosir.</div>'
      : items.map(function (it) {
          return '<a class="pal-item" href="' + it.u + '"><b>' + it.t + '</b><small>' + it.g + '</small></a>';
        }).join('');
  }
  window.openPalette = openPalette;
  if (backdrop) {
    backdrop.addEventListener('click', function (ev) { if (ev.target === backdrop) closePalette(); });
    document.addEventListener('keydown', function (ev) {
      if ((ev.ctrlKey || ev.metaKey) && ev.key.toLowerCase() === 'k') { ev.preventDefault(); openPalette(); }
      if (ev.key === 'Escape') closePalette();
    });
    input.addEventListener('input', function () {
      var q = input.value.trim().toLowerCase();
      if (q.length < 2) { render([]); return; }
      loadIndex().then(function (idx) {
        render(idx.filter(function (it) { return (it.t + ' ' + it.g).toLowerCase().indexOf(q) !== -1; }).slice(0, 12));
      });
    });
  }

  /* ---------- Kalkulator kesehatan ---------- */
  var calc = document.getElementById('calcWeight');
  if (calc) {
    var out = document.getElementById('calcOut');
    var upd = function () {
      var w = parseInt(calc.value || '60', 10);
      var lbl = document.getElementById('calcWeightVal');
      if (lbl) lbl.textContent = w + ' kg';
      var liter = (w * 0.033);
      var bmiW = document.getElementById('calcHeight').value;
      var h = parseFloat(bmiW || '170') / 100;
      var bmi = (w / (h * h));
      var cat = bmi < 18.5 ? 'Kurang' : bmi < 25 ? 'Normal' : bmi < 30 ? 'Berlebih' : 'Obesitas';
      out.innerHTML =
        '<div class="grid grid-3" style="margin-top:18px">' +
        '<div class="card"><h3>' + (liter - 0.2).toFixed(1) + '–' + (liter + 0.2).toFixed(1) + ' L</h3><p>Kebutuhan air harian (EFSA ±33 ml/kg)</p></div>' +
        '<div class="card"><h3>' + bmi.toFixed(1) + '</h3><p>BMI — kategori: <b>' + cat + '</b></p></div>' +
        '<div class="card"><h3>' + Math.round(w * 2) + ' ml</h3><p>Dosis terapi air bambu per hari (±2 ml/kg)</p></div>' +
        '</div>';
    };
    calc.addEventListener('input', upd);
    document.getElementById('calcHeight').addEventListener('input', upd);
    upd();
  }

  /* ---------- Lacak pesanan ---------- */
  var track = document.getElementById('trackBtn');
  if (track) {
    track.addEventListener('click', function () {
      var id = document.getElementById('trackId').value.trim();
      var out = document.getElementById('trackOut');
      if (!id) { out.innerHTML = '<div class="alert alert-err">Masukkan nomor pesanan.</div>'; return; }
      var seed = 0, i; for (i = 0; i < id.length; i++) seed = (seed * 31 + id.charCodeAt(i)) >>> 0;
      var steps = ['Pesanan Diterima', 'Verifikasi & Konfirmasi', 'Persiapan & Pengemasan', 'Serah Terima Kurir', 'Dalam Pengiriman', 'Selesai / Diterima'];
      var prog = 1 + (seed % 4), eta = 2 + (seed % 5), html = '<div class="alert alert-ok">Pesanan <b>' + id.replace(/[<>&]/g, '') + '</b> — estimasi tiba ±' + eta + ' hari kerja</div><ul class="timeline" style="margin-top:14px">';
      steps.forEach(function (s, ix) {
        html += '<li style="' + (ix < prog ? 'border-left:4px solid #16a34a' : 'opacity:.55') + '"><b>' + (ix + 1) + '. ' + s + '</b><br><small>' + (ix < prog ? 'Selesai ✓' : 'Menunggu…') + '</small></li>';
      });
      out.innerHTML = html + '</ul>';
    });
  }

  /* ---------- Filter daftar (kota/kondisi/layanan) ---------- */
  var filterBox = document.getElementById('listFilter');
  if (filterBox) {
    filterBox.addEventListener('input', function () {
      var q = filterBox.value.trim().toLowerCase();
      document.querySelectorAll('[data-filter-item]').forEach(function (el) {
        el.style.display = el.textContent.toLowerCase().indexOf(q) !== -1 ? '' : 'none';
      });
      var n = Array.prototype.filter.call(document.querySelectorAll('[data-filter-item]'), function (el) { return el.style.display !== 'none'; }).length;
      var cnt = document.getElementById('listCount');
      if (cnt) cnt.textContent = n + ' hasil';
    });
  }
})();
