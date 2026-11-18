<?php $__env->startSection('title', 'Pengguna'); ?>
<?php $__env->startSection('content'); ?>
<?php
    $isSuper = auth()->user()->role === 'superadmin';
    $me = auth()->id();
    $canCreate = in_array($tab, ['ob', 'admin']);
    $titles = ['mahasiswa' => 'Mahasiswa', 'ob' => 'Petugas OB', 'admin' => 'Admin'];
?>
<div class="head">
    <div>
        <h1>Pengguna</h1>
        <p>Akun tidak pernah dihapus, hanya dinonaktifkan, supaya riwayat laporan tetap utuh.</p>
    </div>
    <?php if($canCreate): ?><button class="btn btn-primary" data-open="dlg-create">Tambah <?php echo e($tab === 'ob' ? 'petugas OB' : 'admin'); ?></button><?php endif; ?>
</div>

<div class="tabs">
    <a href="<?php echo e(route('admin.users', 'mahasiswa')); ?>" class="<?php echo e($tab === 'mahasiswa' ? 'on' : ''); ?>">Mahasiswa</a>
    <a href="<?php echo e(route('admin.users', 'ob')); ?>" class="<?php echo e($tab === 'ob' ? 'on' : ''); ?>">Petugas OB</a>
    <?php if($isSuper): ?><a href="<?php echo e(route('admin.users', 'admin')); ?>" class="<?php echo e($tab === 'admin' ? 'on' : ''); ?>">Admin</a><?php endif; ?>
</div>

<form class="filters" method="GET">
    <div class="field grow"><label for="q">Cari</label><input id="q" type="text" name="q" value="<?php echo e(request('q')); ?>" placeholder="Nama, email<?php echo e($tab === 'mahasiswa' ? ', atau NIM' : ''); ?>"></div>
    <button class="btn">Cari</button>
</form>

<div class="panel">
    <?php if($users->isEmpty()): ?>
        <div class="empty"><b>Belum ada akun <?php echo e(strtolower($titles[$tab])); ?></b><?php echo e($canCreate ? 'Tambahkan akun lewat tombol di kanan atas.' : 'Mahasiswa muncul di sini setelah mendaftar sendiri.'); ?></div>
    <?php else: ?>
    <div class="tbl-wrap">
        <table class="tbl">
            <thead><tr><th>Nama</th><th>Email</th><?php if($tab === 'mahasiswa'): ?><th>NIM</th><?php endif; ?><th>Status akun</th><th></th></tr></thead>
            <tbody>
            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $usr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><b style="font-weight:600"><?php echo e($usr->name); ?></b></td>
                    <td><?php echo e($usr->email); ?></td>
                    <?php if($tab === 'mahasiswa'): ?><td><?php echo e($usr->nim ?: '-'); ?></td><?php endif; ?>
                    <td><?php if($usr->is_active): ?>Aktif <?php else: ?><span class="badge-off">Nonaktif</span><?php endif; ?></td>
                    <td class="num">
                        <div class="actions" style="justify-content:flex-end">
                        <?php if($usr->id !== $me): ?>
                            <?php if($tab !== 'mahasiswa'): ?>
                                <button class="btn btn-sm" type="button" data-edit
                                    data-action="<?php echo e(route('admin.users.update', $usr)); ?>"
                                    data-name="<?php echo e($usr->name); ?>" data-email="<?php echo e($usr->email); ?>" data-active="<?php echo e($usr->is_active ? 1 : 0); ?>">Ubah</button>
                            <?php endif; ?>
                            <form method="POST" action="<?php echo e(route('admin.users.toggle', $usr)); ?>">
                                <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                <button class="btn btn-sm <?php echo e($usr->is_active ? 'btn-danger' : ''); ?>"><?php echo e($usr->is_active ? 'Nonaktifkan' : 'Aktifkan'); ?></button>
                            </form>
                        <?php endif; ?>
                        </div>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
    <?php if($users->hasPages()): ?><div class="pager"><?php echo e($users->links()); ?></div><?php endif; ?>
    <?php endif; ?>
</div>

<?php if($canCreate): ?>
<dialog id="dlg-create">
    <form method="POST" action="<?php echo e(route('admin.users.store')); ?>">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="role" value="<?php echo e($tab); ?>">
        <h2>Tambah <?php echo e($tab === 'ob' ? 'petugas OB' : 'admin'); ?></h2>
        <div class="field"><label>Nama lengkap</label><input type="text" name="name" required maxlength="100"></div>
        <div class="field"><label>Email</label><input type="email" name="email" required></div>
        <div class="field"><label>Password</label><input type="password" name="password" required minlength="8" autocomplete="new-password"></div>
        <div class="field"><label>Ulangi password</label><input type="password" name="password_confirmation" required minlength="8" autocomplete="new-password"></div>
        <div class="actions"><button class="btn btn-primary">Simpan akun</button><button class="btn" type="button" data-close>Batal</button></div>
    </form>
</dialog>

<dialog id="dlg-edit">
    <form method="POST" action="">
        <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
        <h2>Ubah akun</h2>
        <div class="field"><label>Nama lengkap</label><input type="text" name="name" required maxlength="100"></div>
        <div class="field"><label>Email</label><input type="email" name="email" required></div>
        <div class="field"><label>Reset password</label><input type="password" name="password" minlength="8" autocomplete="new-password"><span class="hint">Kosongkan jika tidak diganti.</span></div>
        <div class="field"><label>Ulangi password baru</label><input type="password" name="password_confirmation" minlength="8" autocomplete="new-password"></div>
        <div class="field"><label>Status akun</label>
            <select name="is_active"><option value="1">Aktif</option><option value="0">Nonaktif</option></select>
        </div>
        <div class="actions"><button class="btn btn-primary">Simpan perubahan</button><button class="btn" type="button" data-close>Batal</button></div>
    </form>
</dialog>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\hadziq\Documents\UAS\Sistem Pengaduan\siakam\resources\views/admin/users.blade.php ENDPATH**/ ?>