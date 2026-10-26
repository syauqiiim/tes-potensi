<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="container py-5" style="max-width: 420px;">
    <div class="card shadow-sm">
        <div class="card-body p-4">
            <h1 class="h4 mb-3">Login Admin</h1>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
            <?php endif; ?>

            <form method="post" action="<?= base_url('login') ?>">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label class="form-label" for="username">Username</label>
                    <input class="form-control" id="username" name="username" value="<?= esc(old('username')) ?>" required autofocus>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="password">Password</label>
                    <input class="form-control" type="password" id="password" name="password" required>
                </div>
                <button class="btn btn-dark w-100" type="submit">Masuk</button>
            </form>
        </div>
    </div>
    <p class="text-center mt-3"><a class="small text-muted" href="<?= base_url() ?>">&laquo; Kembali ke halaman peserta</a></p>
</div>
<?= $this->endSection() ?>
