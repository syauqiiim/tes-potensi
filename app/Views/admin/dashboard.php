<?= $this->extend('layout/admin') ?>

<?= $this->section('content') ?>
<h1 class="h4 mb-3">Dashboard</h1>

<?php if ($soalAktif < (int) $set['jumlah_soal']): ?>
    <div class="alert alert-warning">
        Soal aktif baru <strong><?= (int) $soalAktif ?></strong> dari target <strong><?= (int) $set['jumlah_soal'] ?></strong>.
        Peserta hanya akan mendapat <?= (int) $soalAktif ?> soal sampai Anda menambah soal.
        <a href="<?= base_url('admin/soal/tambah') ?>" class="alert-link">Tambah soal</a>
    </div>
<?php endif; ?>
<?php if (! (int) $set['tes_dibuka']): ?>
    <div class="alert alert-secondary">Tes sedang <strong>ditutup</strong>. Buka di menu Pengaturan agar peserta baru bisa mulai.</div>
<?php endif; ?>

<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3"><div class="card"><div class="card-body"><div class="text-muted small">Soal aktif / total</div><div class="fs-3"><?= (int) $soalAktif ?> / <?= (int) $soalTotal ?></div></div></div></div>
    <div class="col-6 col-lg-3"><div class="card"><div class="card-body"><div class="text-muted small">Peserta terdaftar</div><div class="fs-3"><?= (int) $pesertaTotal ?></div></div></div></div>
    <div class="col-6 col-lg-3"><div class="card"><div class="card-body"><div class="text-muted small">Sedang mengerjakan</div><div class="fs-3"><?= (int) $sesiBerjalan ?></div></div></div></div>
    <div class="col-6 col-lg-3"><div class="card"><div class="card-body"><div class="text-muted small">Sudah selesai</div><div class="fs-3"><?= (int) $sesiSelesai ?></div></div></div></div>
</div>

<div class="card">
    <div class="card-body">
        <h2 class="h6">Pengaturan saat ini</h2>
        <p class="mb-0">
            Durasi <strong><?= (int) $set['durasi_menit'] ?> menit</strong> &middot;
            Jumlah soal <strong><?= (int) $set['jumlah_soal'] ?></strong> &middot;
            Acak soal <strong><?= (int) $set['acak_soal'] ? 'ya' : 'tidak' ?></strong> &middot;
            Skor ditampilkan ke peserta <strong><?= (int) $set['tampil_skor'] ? 'ya' : 'tidak' ?></strong>
        </p>
    </div>
</div>
<?= $this->endSection() ?>
