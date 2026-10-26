<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<?php
$dijawab = 0;
foreach ($semua as $s) {
    if ($s['pilihan'] !== null) {
        $dijawab++;
    }
}
$opsi = ['A' => $soal['opsi_a'], 'B' => $soal['opsi_b'], 'C' => $soal['opsi_c']];
?>

<div class="topbar sticky-top bg-white border-bottom shadow-sm">
    <div class="container d-flex justify-content-between align-items-center py-2">
        <div>
            <strong>Tes Potensi</strong>
            <span class="text-muted ms-2">Soal <?= (int) $no ?> dari <?= (int) $total ?></span>
        </div>
        <div class="text-end">
            <span class="small text-muted me-2">Sisa waktu</span>
            <span id="timer" class="timer">--:--</span>
        </div>
    </div>
</div>

<div class="container py-4">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <p class="fs-5 mb-4"><?= nl2br(esc($soal['teks_soal'])) ?></p>

                    <?php foreach ($opsi as $huruf => $teks): ?>
                        <div class="form-check opsi mb-2">
                            <input class="form-check-input" type="radio" name="pilihan"
                                   id="opt-<?= $huruf ?>" value="<?= $huruf ?>"
                                   <?= $soal['pilihan'] === $huruf ? 'checked' : '' ?>>
                            <label class="form-check-label w-100" for="opt-<?= $huruf ?>">
                                <strong><?= $huruf ?>.</strong> <?= esc($teks) ?>
                            </label>
                        </div>
                    <?php endforeach; ?>

                    <div class="mt-3 d-flex justify-content-between align-items-center">
                        <button type="button" id="btn-hapus" class="btn btn-link btn-sm text-muted p-0">Hapus jawaban</button>
                        <span id="status-simpan" class="small text-muted"></span>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between mt-3">
                <?php if ($no > 1): ?>
                    <a class="btn btn-outline-secondary" data-nav href="<?= base_url('tes/soal/' . ($no - 1)) ?>">&laquo; Sebelumnya</a>
                <?php else: ?>
                    <span></span>
                <?php endif; ?>

                <?php if ($no < $total): ?>
                    <a class="btn btn-primary" data-nav href="<?= base_url('tes/soal/' . ($no + 1)) ?>">Berikutnya &raquo;</a>
                <?php else: ?>
                    <button type="button" id="btn-selesai" class="btn btn-success">Selesai</button>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="h6">Navigasi soal</h2>
                    <p class="small text-muted mb-2">Terjawab: <span id="jumlah-dijawab"><?= $dijawab ?></span> / <?= (int) $total ?></p>
                    <div class="panel-nomor">
                        <?php foreach ($semua as $s): ?>
                            <?php
                            $n   = (int) $s['urutan'];
                            $cls = 'nomor';
                            if ($s['pilihan'] !== null) {
                                $cls .= ' dijawab';
                            }
                            if ($n === (int) $no) {
                                $cls .= ' aktif';
                            }
                            ?>
                            <a id="nav-<?= $n ?>" class="<?= $cls ?>" data-nav href="<?= base_url('tes/soal/' . $n) ?>"><?= $n ?></a>
                        <?php endforeach; ?>
                    </div>
                    <p class="small text-muted mt-3 mb-3">Biru = sudah dijawab, kuning = sedang dibuka, putih = belum.</p>
                    <button type="button" id="btn-selesai-panel" class="btn btn-success w-100">Selesai &amp; Kirim</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Form kirim jawaban (dipakai tombol Selesai dan auto-submit) -->
<form id="form-selesai" method="post" action="<?= base_url('tes/selesai') ?>">
    <?= csrf_field() ?>
</form>

<!-- Pop-up reminder -->
<div class="modal fade" id="modal-reminder" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pengingat waktu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <p id="reminder-teks" class="mb-0 fs-5"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Lanjutkan mengerjakan</button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
window.TES = <?= json_encode([
    'sesiId'       => (int) $sesi['id'],
    'soalId'       => (int) $soal['soal_id'],
    'urutan'       => (int) $no,
    'total'        => (int) $total,
    'waktuSelesai' => strtotime($sesi['waktu_selesai']) * 1000,
    'serverNow'    => time() * 1000,
    'totalDetik'   => strtotime($sesi['waktu_selesai']) - strtotime($sesi['waktu_mulai']),
    'reminder'     => [15, 5, 1],
    'urlSimpan'    => base_url('tes/simpan-jawaban'),
    'urlSisa'      => base_url('tes/sisa-waktu'),
    'urlHasil'     => base_url('tes/hasil'),
    'csrfHeader'   => csrf_header(),
    'csrfToken'    => csrf_hash(),
], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
</script>
<script src="<?= base_url('assets/js/tes.js') ?>"></script>
<?= $this->endSection() ?>
