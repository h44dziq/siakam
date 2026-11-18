<?php $__env->startSection('title', 'Masuk'); ?>
<?php $__env->startSection('content'); ?>
<h2>Masuk ke SIAKAM</h2>
<p class="muted">Gunakan email kampus untuk mahasiswa, atau akun dari admin untuk petugas.</p>
<form method="POST" action="<?php echo e(route('login')); ?>">
    <?php echo csrf_field(); ?>
    <?php if($errors->any()): ?><div class="flash err" style="margin:0" role="alert"><?php echo e($errors->first()); ?></div><?php endif; ?>
    <div class="field">
        <label for="email">Email</label>
        <input id="email" type="email" name="email" value="<?php echo e(old('email')); ?>" required autofocus autocomplete="username">
    </div>
    <div class="field">
        <label for="password">Password</label>
        <input id="password" type="password" name="password" required autocomplete="current-password">
    </div>
    <label class="check"><input type="checkbox" name="remember" value="1"> Ingat saya di perangkat ini</label>
    <button class="btn btn-primary">Masuk</button>
</form>
<p class="alt">Mahasiswa belum punya akun? <a href="<?php echo e(route('register')); ?>">Daftar</a></p>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.guest', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\hadziq\Documents\UAS\Sistem Pengaduan\siakam\resources\views/auth/login.blade.php ENDPATH**/ ?>