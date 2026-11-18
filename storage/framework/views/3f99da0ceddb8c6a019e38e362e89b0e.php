<?php $__env->startSection('title', 'Profil'); ?>
<?php $__env->startSection('content'); ?>
<div class="head"><h1>Profil</h1></div>
<form class="panel pad" method="POST" action="<?php echo e(route('profil.update')); ?>" style="max-width:560px">
    <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
    <div class="form-grid">
        <div class="field full">
            <label for="name">Nama</label>
            <input id="name" type="text" name="name" value="<?php echo e(old('name', auth()->user()->name)); ?>" required>
        </div>
        <div class="field full">
            <label for="email">Email</label>
            <input id="email" type="email" value="<?php echo e(auth()->user()->email); ?>" disabled>
            <span class="hint">Email tidak bisa diubah setelah akun dibuat.</span>
        </div>
        <div class="field full"><h2 style="margin-top:8px">Ganti password</h2><span class="hint">Kosongkan jika tidak ingin mengganti.</span></div>
        <div class="field full">
            <label for="current_password">Password saat ini</label>
            <input id="current_password" type="password" name="current_password" autocomplete="current-password">
        </div>
        <div class="field">
            <label for="password">Password baru</label>
            <input id="password" type="password" name="password" autocomplete="new-password">
        </div>
        <div class="field">
            <label for="password_confirmation">Ulangi password baru</label>
            <input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password">
        </div>
    </div>
    <div class="actions" style="margin-top:24px"><button class="btn btn-primary">Simpan profil</button></div>
</form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\hadziq\Documents\UAS\Sistem Pengaduan\siakam\resources\views/profil/edit.blade.php ENDPATH**/ ?>