<?= $this->extend('layout/admin') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">Bank Soal</h1>
    <a class="btn btn-primary" href="<?= base_url('admin/soal/tambah') ?>">+ Tambah Soal</a>
</div>

<p class="text-muted">
    Soal aktif: <strong><?= (int) $soalAktif ?></strong> (target <?= (int) $set['jumlah_soal'] ?>).
    <?php if ($soalAktif < (int) $set['jumlah_soal']): ?>
        <span class="text-warning">Belum mencapai target.</span>
    <?php endif; ?>
</p>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width:60px">No</th>
                    <th>Soal</th>
                    <th>Kategori</th>
                    <th style="width:70px">Kunci</th>
                    <th style="width:90px">Status</th>
                    <th style="width:170px">Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php if (! $daftar): ?>
                <tr><td colspan="6" class="text-center text-muted py-4">Belum ada soal.</td></tr>
            <?php endif; ?>
            <?php foreach ($daftar as $s): ?>
                <tr class="<?= $s['aktif'] ? '' : 'text-muted' ?>">
                    <td><?= esc($s['nomor_urut'] ?? '-') ?></td>
                    <td><?= esc(mb_strimwidth($s['teks_soal'], 0, 90, '...')) ?></td>
                    <td><?= esc($s['kategori'] ?? '-') ?></td>
                    <td><span class="badge text-bg-dark"><?= esc($s['kunci']) ?></span></td>
                    <td>
                        <?php if ($s['aktif']): ?>
                            <span class="badge text-bg-success">Aktif</span>
                        <?php else: ?>
                            <span class="badge text-bg-secondary">Nonaktif</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a class="btn btn-sm btn-outline-primary" href="<?= base_url('admin/soal/edit/' . (int) $s['id']) ?>">Edit</a>
                            <form method="post" action="<?= base_url('admin/soal/toggle/' . (int) $s['id']) ?>">
                                <?= csrf_field() ?>
                                <button class="btn btn-sm btn-outline-secondary" type="submit"><?= $s['aktif'] ? 'Nonaktifkan' : 'Aktifkan' ?></button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
