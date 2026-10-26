<?= $this->extend('layout/admin') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">Hasil Peserta</h1>
    <a class="btn btn-outline-success" href="<?= base_url('admin/hasil/ekspor') ?>">Ekspor CSV</a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nama</th>
                    <th>Email / Kode</th>
                    <th>Mulai</th>
                    <th>Dikirim</th>
                    <th>Status</th>
                    <th class="text-end">Benar</th>
                    <th class="text-end">Skor</th>
                </tr>
            </thead>
            <tbody>
            <?php if (! $daftar): ?>
                <tr><td colspan="7" class="text-center text-muted py-4">Belum ada peserta yang mengerjakan.</td></tr>
            <?php endif; ?>
            <?php foreach ($daftar as $r): ?>
                <tr>
                    <td><?= esc($r['nama']) ?></td>
                    <td><?= esc($r['kode']) ?></td>
                    <td><?= esc($r['waktu_mulai']) ?></td>
                    <td><?= esc($r['waktu_kirim'] ?? '-') ?></td>
                    <td>
                        <?php if ($r['status'] === 'selesai'): ?>
                            <span class="badge text-bg-success">Selesai</span>
                        <?php else: ?>
                            <span class="badge text-bg-warning">Berjalan</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-end"><?= $r['jumlah_benar'] === null ? '-' : (int) $r['jumlah_benar'] . ' / ' . (int) $r['jumlah_soal'] ?></td>
                    <td class="text-end fw-bold"><?= $r['skor'] === null ? '-' : esc($r['skor']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
