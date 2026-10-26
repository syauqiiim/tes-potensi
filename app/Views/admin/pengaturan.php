<?= $this->extend('layout/admin') ?>

<?= $this->section('content') ?>
<h1 class="h4 mb-3">Pengaturan Tes</h1>

<div class="card">
    <div class="card-body">
        <form method="post" action="<?= base_url('admin/pengaturan') ?>">
            <?= csrf_field() ?>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label" for="durasi_menit">Durasi tes (menit)</label>
                    <input class="form-control" type="number" min="1" max="600" id="durasi_menit" name="durasi_menit" value="<?= esc(old('durasi_menit', $set['durasi_menit'])) ?>" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label" for="jumlah_soal">Jumlah soal</label>
                    <input class="form-control" type="number" min="1" max="500" id="jumlah_soal" name="jumlah_soal" value="<?= esc(old('jumlah_soal', $set['jumlah_soal'])) ?>" required>
                </div>
            </div>

            <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" id="acak_soal" name="acak_soal" value="1" <?= $set['acak_soal'] ? 'checked' : '' ?>>
                <label class="form-check-label" for="acak_soal">Acak urutan soal untuk tiap peserta</label>
            </div>
            <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" id="tes_dibuka" name="tes_dibuka" value="1" <?= $set['tes_dibuka'] ? 'checked' : '' ?>>
                <label class="form-check-label" for="tes_dibuka">Tes dibuka (peserta baru boleh mulai)</label>
            </div>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="tampil_skor" name="tampil_skor" value="1" <?= $set['tampil_skor'] ? 'checked' : '' ?>>
                <label class="form-check-label" for="tampil_skor">Tampilkan skor langsung ke peserta</label>
            </div>

            <div class="alert alert-info small">Perubahan durasi dan jumlah soal hanya berlaku untuk peserta yang memulai setelah ini; sesi yang sudah berjalan tidak berubah.</div>

            <hr>
            <h2 class="h6">Ganti password admin</h2>
            <div class="mb-4" style="max-width: 360px;">
                <input class="form-control" type="password" name="password_baru" autocomplete="new-password" placeholder="Kosongkan jika tidak diganti (min. 8 karakter)">
            </div>

            <button class="btn btn-primary" type="submit">Simpan pengaturan</button>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
