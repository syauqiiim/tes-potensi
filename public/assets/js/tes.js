/* Timer berbasis server, pop-up reminder, simpan otomatis, dan auto-submit.
   Konfigurasi dikirim dari view lewat window.TES. */
(function () {
    'use strict';

    var C = window.TES;
    if (!C) { return; }

    var offset = C.serverNow - Date.now();   // koreksi selisih jam browser vs server
    var waktuSelesai = C.waktuSelesai;       // ms (waktu server)
    var timerEl = document.getElementById('timer');
    var statusEl = document.getElementById('status-simpan');
    var formSelesai = document.getElementById('form-selesai');
    var antrian = Promise.resolve();         // semua penyimpanan berjalan berurutan
    var sudahKirim = false;
    var interval = null;

    /* ---------- util waktu ---------- */
    function sisaDetik() {
        return Math.floor((waktuSelesai - (Date.now() + offset)) / 1000);
    }

    function format(detik) {
        detik = Math.max(0, detik);
        var m = Math.floor(detik / 60);
        var s = detik % 60;
        return (m < 10 ? '0' : '') + m + ':' + (s < 10 ? '0' : '') + s;
    }

    /* ---------- kirim akhir ---------- */
    function akhiri() {
        if (sudahKirim) { return; }
        sudahKirim = true;
        if (interval) { clearInterval(interval); }
        antrian.then(function () { formSelesai.submit(); });
    }

    /* ---------- simpan jawaban ---------- */
    function setStatus(ok) {
        if (!statusEl) { return; }
        statusEl.textContent = ok ? 'Tersimpan' : 'Gagal menyimpan, periksa koneksi';
        statusEl.className = 'small ' + (ok ? 'text-muted' : 'gagal');
    }

    function kirimJawaban(payload, percobaan) {
        var headers = {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        };
        headers[C.csrfHeader] = C.csrfToken;

        return fetch(C.urlSimpan, {
            method: 'POST',
            headers: headers,
            body: JSON.stringify(payload),
            credentials: 'same-origin'
        }).then(function (r) {
            if (r.status === 403) {
                return r.json().then(function (d) {
                    if (d && d.status === 'habis') { akhiri(); } else { setStatus(false); }
                }, function () { setStatus(false); });
            }
            if (!r.ok) { throw new Error('gagal'); }
            setStatus(true);
        }).catch(function () {
            if (percobaan < 3) {
                return new Promise(function (res) { setTimeout(res, 1000 * (percobaan + 1)); })
                    .then(function () { return kirimJawaban(payload, percobaan + 1); });
            }
            setStatus(false);
        });
    }

    function simpan(soalId, pilihan) {
        if (statusEl) { statusEl.textContent = 'Menyimpan...'; statusEl.className = 'small text-muted'; }
        antrian = antrian.then(function () {
            return kirimJawaban({ soal_id: soalId, pilihan: pilihan }, 0);
        });
    }

    function tandaiDijawab(ya) {
        var el = document.getElementById('nav-' + C.urutan);
        if (el) { el.classList.toggle('dijawab', ya); }
        var jml = document.querySelectorAll('.panel-nomor .nomor.dijawab').length;
        var out = document.getElementById('jumlah-dijawab');
        if (out) { out.textContent = jml; }
    }

    var radios = document.querySelectorAll('input[name="pilihan"]');
    Array.prototype.forEach.call(radios, function (el) {
        el.addEventListener('change', function () {
            simpan(C.soalId, el.value);
            tandaiDijawab(true);
        });
    });

    var btnHapus = document.getElementById('btn-hapus');
    if (btnHapus) {
        btnHapus.addEventListener('click', function () {
            Array.prototype.forEach.call(radios, function (el) { el.checked = false; });
            simpan(C.soalId, null);
            tandaiDijawab(false);
        });
    }

    /* ---------- navigasi: tunggu penyimpanan selesai dulu ---------- */
    Array.prototype.forEach.call(document.querySelectorAll('[data-nav]'), function (a) {
        a.addEventListener('click', function (e) {
            e.preventDefault();
            var href = a.getAttribute('href');
            antrian.then(function () { window.location.href = href; });
        });
    });

    /* ---------- tombol selesai ---------- */
    function konfirmasiSelesai() {
        var belum = C.total - document.querySelectorAll('.panel-nomor .nomor.dijawab').length;
        var pesan = belum > 0
            ? 'Masih ada ' + belum + ' soal yang belum dijawab. Yakin ingin menyelesaikan tes?'
            : 'Kirim jawaban dan selesaikan tes sekarang?';
        if (window.confirm(pesan)) { akhiri(); }
    }
    ['btn-selesai', 'btn-selesai-panel'].forEach(function (id) {
        var b = document.getElementById(id);
        if (b) { b.addEventListener('click', konfirmasiSelesai); }
    });

    /* ---------- reminder ---------- */
    var KUNCI = 'tes_reminder_' + C.sesiId;
    var sudahTampil = {};
    try { sudahTampil = JSON.parse(sessionStorage.getItem(KUNCI) || '{}'); } catch (e) { sudahTampil = {}; }

    var modalEl = document.getElementById('modal-reminder');
    var modal = (modalEl && window.bootstrap) ? new window.bootstrap.Modal(modalEl) : null;

    function tampilkanReminder(menit) {
        var teks = menit === 1
            ? 'Sisa waktu 1 menit! Periksa soal yang belum dijawab.'
            : 'Sisa waktu ' + menit + ' menit.';
        var el = document.getElementById('reminder-teks');
        if (el) { el.textContent = teks; }
        if (modal) { modal.show(); } else { window.alert(teks); }
    }

    function cekReminder(sisa) {
        var daftar = C.reminder.slice().sort(function (a, b) { return a - b; });
        for (var i = 0; i < daftar.length; i++) {
            var t = daftar[i];
            if (sisa <= t * 60) {
                // Titik waktu terkecil yang sudah terlewati; hanya tampil sekali,
                // dan tidak tampil bila durasi tes sendiri tidak lebih panjang dari titik itu.
                if (t * 60 < C.totalDetik && !sudahTampil[t]) {
                    daftar.forEach(function (x) { if (x >= t) { sudahTampil[x] = true; } });
                    try { sessionStorage.setItem(KUNCI, JSON.stringify(sudahTampil)); } catch (e) { /* abaikan */ }
                    tampilkanReminder(t);
                }
                break;
            }
        }
    }

    /* ---------- detak timer ---------- */
    function tick() {
        var sisa = sisaDetik();
        if (timerEl) {
            timerEl.textContent = format(sisa);
            timerEl.classList.toggle('merah', sisa <= 300);
        }
        if (sisa <= 0) {
            akhiri();
            return;
        }
        cekReminder(sisa);
    }

    /* ---------- sinkron ulang dengan server ---------- */
    function sinkron() {
        fetch(C.urlSisa, {
            credentials: 'same-origin',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        }).then(function (r) { return r.json(); }).then(function (d) {
            if (d.status === 'selesai') { window.location.href = C.urlHasil; return; }
            if (typeof d.server_now === 'number' && typeof d.waktu_selesai === 'number') {
                offset = d.server_now - Date.now();
                waktuSelesai = d.waktu_selesai;
            }
        }).catch(function () { /* koneksi putus: pakai hitungan lokal */ });
    }

    tick();
    interval = setInterval(tick, 1000);
    setInterval(sinkron, 45000);
})();
