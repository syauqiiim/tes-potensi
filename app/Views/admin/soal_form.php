<?= $this->extend('layout/admin') ?>

<?= $this->section('content') ?>
<?php
$v = static function (string $k, $default = '') use ($soal) {
    return old($k, $soal[$k] ?? $default);
};
$kunciSekarang = $v('kunci');
$aktifSekarang = $soal ? (bool) old('aktif', $soal['aktif']) : true;
if (old('teks_soal') !== null && $soal === null) {
    $aktifSekarang = (bool) old('aktif');
}
?>
<h1 class="h4 mb-3"><?= esc($title) ?></h1>

<div class="card">
    <div class="card-body">
        <form method="post" action="<?= esc($aksi, 'attr') ?>">
            <?= csrf_field() ?>

            <div class="mb-3">
                <label class="form-label" for="teks_soal">Teks soal</label>
                <textarea class="form-control" id="teks_soal" name="teks_soal" rows="4" required><?= esc($v('teks_soal')) ?></textarea>
            </div>

            <?php foreach (['a' => 'A', 'b' => 'B', 'c' => 'C'] as $k => $huruf): ?>
                <div class="mb-3">
                    <label class="form-label" for="opsi_<?= $k ?>">Opsi <?= $huruf ?></label>
                    <input class="form-control" id="opsi_<?= $k ?>" name="opsi_<?= $k ?>" value="<?= esc($v('opsi_' . $k)) ?>" required maxlength="255">
                </div>
            <?php endforeach; ?>

            <div class="mb-3">
                <label class="form-label d-block">Kunci jawaban</label>
                <?php foreach (['A', 'B', 'C'] as $h): ?>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="kunci" id="kunci_<?= $h ?>" value="<?= $h ?>" <?= $kunciSekarang === $h ? 'checked' : '' ?> required>
                        <label class="form-check-label" for="kunci_<?= $h ?>"><?= $h ?></label>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label" for="nomor_urut">Nomor urut (opsional)</label>
                    <input class="form-control" type="number" min="1" id="nomor_urut" name="nomor_urut" value="<?= esc($v('nomor_urut')) ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label" for="kategori">Kategori (opsional)</label>
                    <input class="form-control" id="kategori" name="kategori" list="daftar-kategori" value="<?= esc($v('kategori')) ?>" maxlength="50">
                    <datalist id="daftar-kategori"><option value="verbal"><option value="numerik"><option value="logika"></datalist>
                </div>
            </div>

            <div class="form-check mb-4">
                <input class="form-check-input" type="checkbox" id="aktif" name="aktif" value="1" <?= $aktifSekarang ? 'checked' : '' ?>>
                <label class="form-check-label" for="aktif">Soal aktif (dipakai dalam tes)</label>
            </div>

            <button class="btn btn-primary" type="submit">Simpan</button>
            <a class="btn btn-link" href="<?= base_url('admin/soal') ?>">Batal</a>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
