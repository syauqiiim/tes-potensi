<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="container py-5" style="max-width: 520px;">
    <div class="card shadow-sm">
        <div class="card-body p-4">
            <h1 class="h4 mb-1">Tes Potensi</h1>
            <p class="text-muted">Isi identitas Anda untuk melanjutkan.</p>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
            <?php endif; ?>
            <?php if (! (int) $set['tes_dibuka']): ?>
                <div class="alert alert-warning">Tes belum dibuka oleh admin. Peserta baru belum dapat memulai.</div>
            <?php endif; ?>

            <form method="post" action="<?= base_url('identitas') ?>">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label class="form-label" for="nama">Nama lengkap</label>
                    <input class="form-control" id="nama" name="nama" value="<?= esc(old('nama')) ?>" required maxlength="100">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="kode">Email / kode peserta</label>
                    <input class="form-control" id="kode" name="kode" value="<?= esc(old('kode')) ?>" required maxlength="100">
                    <div class="form-text">Gunakan email atau kode yang diberikan panitia. Satu peserta hanya dapat mengikuti tes satu kali.</div>
                </div>
                <button class="btn btn-primary w-100" type="submit">Lanjut</button>
            </form>
        </div>
    </div>
    <p class="text-center mt-3"><a class="small text-muted" href="<?= base_url('login') ?>">Login admin</a></p>
</div>
<?= $this->endSection() ?>
