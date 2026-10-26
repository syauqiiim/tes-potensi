<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="container py-5" style="max-width: 640px;">
    <div class="card shadow-sm">
        <div class="card-body p-4">
            <h1 class="h4">Instruksi Tes</h1>
            <p class="text-muted mb-3">Halo, <strong><?= esc($peserta['nama'] ?? '') ?></strong>.</p>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
            <?php endif; ?>

            <ul>
                <li>Tes terdiri dari maksimal <strong><?= (int) $set['jumlah_soal'] ?> soal</strong> pilihan ganda (A, B, C).</li>
                <li>Waktu pengerjaan <strong><?= (int) $set['durasi_menit'] ?> menit</strong>, dihitung sejak tombol Mulai ditekan.</li>
                <li>Anda akan mendapat pengingat saat sisa waktu 15, 5, dan 1 menit.</li>
                <li>Jawaban tersimpan otomatis. Jika halaman di-refresh, waktu tidak di-reset.</li>
                <li>Saat waktu habis, jawaban dikirim otomatis dan tes dikunci.</li>
                <li>Tes hanya dapat diikuti satu kali.</li>
            </ul>

            <form method="post" action="<?= base_url('tes/mulai') ?>">
                <?= csrf_field() ?>
                <button class="btn btn-success btn-lg w-100" type="submit">Mulai Tes</button>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
