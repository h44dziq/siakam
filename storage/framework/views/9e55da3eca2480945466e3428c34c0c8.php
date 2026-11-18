<!DOCTYPE html>
<html lang="id">
<head>
<?php echo $__env->make('layouts.head', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</head>
<body>
<?php
    $u = auth()->user();
    $nav = match (true) {
        $u->role === 'mahasiswa' => [
            ['mahasiswa.dashboard', 'Riwayat laporan', ['mahasiswa.dashboard', 'laporan.show']],
            ['mahasiswa.laporan.create', 'Buat laporan', ['mahasiswa.laporan.*']],
            ['profil.edit', 'Profil', ['profil.*']],
        ],
        $u->role === 'ob' => [
            ['ob.dashboard', 'Laporan masuk', ['ob.dashboard', 'laporan.show']],
            ['profil.edit', 'Profil', ['profil.*']],
        ],
        default => [
            ['admin.dashboard', 'Ringkasan', ['admin.dashboard']],
            ['admin.laporan.index', 'Laporan', ['admin.laporan.*', 'laporan.show']],
            ['admin.master', 'Data master', ['admin.master']],
            ['admin.users', 'Pengguna', ['admin.users*']],
            ['profil.edit', 'Profil', ['profil.*']],
        ],
    };
?>
<div class="shell">
    <aside class="side">
        <a class="brand" href="<?php echo e(route($u->homeRoute())); ?>"><span class="mark"></span>siakam</a>
        <nav class="nav" aria-label="Menu utama">
            <?php $__currentLoopData = $nav; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$route, $label, $patterns]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route($route)); ?>" class="<?php echo e(request()->routeIs(...$patterns) ? 'on' : ''); ?>"><?php echo e($label); ?></a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </nav>
        <div class="me">
            <span class="avatar"><?php echo e($u->initials()); ?></span>
            <div class="who"><b><?php echo e($u->name); ?></b><span><?php echo e($u->roleLabel()); ?></span></div>
            <form method="POST" action="<?php echo e(route('logout')); ?>"><?php echo csrf_field(); ?><button class="linkbtn">Keluar</button></form>
        </div>
    </aside>
    <main class="main">
        <?php if(session('ok')): ?><div class="flash ok" role="status"><?php echo e(session('ok')); ?></div><?php endif; ?>
        <?php if($errors->any()): ?>
            <div class="flash err" role="alert">
                <ul><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul>
            </div>
        <?php endif; ?>
        <?php echo $__env->yieldContent('content'); ?>
    </main>
</div>
</body>
</html>
<?php /**PATH C:\Users\hadziq\Documents\UAS\Sistem Pengaduan\siakam\resources\views/layouts/app.blade.php ENDPATH**/ ?>