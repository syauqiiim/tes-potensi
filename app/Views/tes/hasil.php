<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="container py-5" style="max-width: 520px;">
    <div class="card shadow-sm text-center">
        <div class="card-body p-4">
            <h1 class="h4 mb-3">Tes selesai</h1>
            <p class="text-muted">Terima kasih, <strong><?= esc($peserta['nama'] ?? '') ?></strong>.</p>

            <?php if ((int) $set['tampil_skor']): ?>
                <div class="display-3 fw-bold text-primary"><?= esc(rtrim(rtrim(number_format((float) $sesi['skor'], 2, '.', ''), '0'), '.')) ?></div>
                <p class="mb-0">Skor Anda (0-100)</p>
                <p class="text-muted small">Benar <?= (int) $sesi['jumlah_benar'] ?> dari <?= (int) $sesi['jumlah_soal'] ?> soal</p>
            <?php else: ?>
                <div class="alert alert-success mb-0">Jawaban Anda sudah tersimpan. Hasil akan diumumkan oleh panitia.</div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
