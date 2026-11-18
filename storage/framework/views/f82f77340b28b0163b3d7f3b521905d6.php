<?php $__env->startSection('title', 'Data master'); ?>
<?php $__env->startSection('content'); ?>
<div class="head">
    <div>
        <h1>Data master</h1>
        <p>Perubahan di sini langsung muncul di form laporan. Data yang sudah dipakai laporan tidak bisa dihapus.</p>
    </div>
</div>
<div class="tabs">
    <a href="<?php echo e(route('admin.master')); ?>" class="<?php echo e($tab === 'fasilitas' ? 'on' : ''); ?>">Gedung dan ruangan</a>
    <a href="<?php echo e(route('admin.master', ['tab' => 'kategori'])); ?>" class="<?php echo e($tab === 'kategori' ? 'on' : ''); ?>">Kategori masalah</a>
</div>

<?php if($tab === 'fasilitas'): ?>
    <form class="inline" method="POST" action="<?php echo e(route('admin.gedung.store')); ?>" style="max-width:460px; margin-bottom:20px">
        <?php echo csrf_field(); ?>
        <input type="text" name="name" placeholder="Nama gedung baru" required maxlength="100" aria-label="Nama gedung baru">
        <button class="btn btn-primary">Tambah gedung</button>
    </form>
    <div class="panel">
        <?php $__empty_1 = true; $__currentLoopData = $buildings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="bld">
                <div class="inline">
                    <form class="inline" style="flex:1" method="POST" action="<?php echo e(route('admin.gedung.update', $b)); ?>">
                        <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                        <input type="text" name="name" value="<?php echo e($b->name); ?>" required maxlength="100" aria-label="Nama gedung" style="font-weight:600">
                        <button class="btn btn-sm">Simpan</button>
                    </form>
                    <form method="POST" action="<?php echo e(route('admin.gedung.destroy', $b)); ?>" onsubmit="return confirm('Hapus <?php echo e(e($b->name)); ?>?')">
                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                        <button class="btn btn-sm btn-danger">Hapus</button>
                    </form>
                </div>
                <div class="rooms">
                    <?php $__currentLoopData = $b->rooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="inline">
                            <form class="inline" style="flex:1" method="POST" action="<?php echo e(route('admin.ruangan.update', $r)); ?>">
                                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                                <input type="text" name="name" value="<?php echo e($r->name); ?>" required maxlength="100" aria-label="Nama ruangan">
                                <button class="btn btn-sm">Simpan</button>
                            </form>
                            <form method="POST" action="<?php echo e(route('admin.ruangan.destroy', $r)); ?>" onsubmit="return confirm('Hapus <?php echo e(e($r->name)); ?>?')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <form class="inline" method="POST" action="<?php echo e(route('admin.ruangan.store', $b)); ?>" style="padding-left:16px">
                        <?php echo csrf_field(); ?>
                        <input type="text" name="name" placeholder="Ruangan baru di <?php echo e($b->name); ?>" required maxlength="100" aria-label="Ruangan baru">
                        <button class="btn btn-sm">Tambah ruangan</button>
                    </form>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="empty"><b>Belum ada gedung</b>Tambahkan gedung pertama, lalu ruangannya.</div>
        <?php endif; ?>
    </div>
<?php else: ?>
    <form class="inline" method="POST" action="<?php echo e(route('admin.kategori.store')); ?>" style="max-width:460px; margin-bottom:20px">
        <?php echo csrf_field(); ?>
        <input type="text" name="name" placeholder="Nama kategori baru" required maxlength="100" aria-label="Nama kategori baru">
        <button class="btn btn-primary">Tambah kategori</button>
    </form>
    <div class="panel">
        <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="bld">
                <div class="inline">
                    <form class="inline" style="flex:1" method="POST" action="<?php echo e(route('admin.kategori.update', $c)); ?>">
                        <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                        <input type="text" name="name" value="<?php echo e($c->name); ?>" required maxlength="100" aria-label="Nama kategori">
                        <button class="btn btn-sm">Simpan</button>
                    </form>
                    <form method="POST" action="<?php echo e(route('admin.kategori.destroy', $c)); ?>" onsubmit="return confirm('Hapus <?php echo e(e($c->name)); ?>?')">
                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                        <button class="btn btn-sm btn-danger">Hapus</button>
                    </form>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="empty"><b>Belum ada kategori</b>Tambahkan jenis masalah yang bisa dipilih mahasiswa.</div>
        <?php endif; ?>
    </div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\hadziq\Documents\UAS\Sistem Pengaduan\siakam\resources\views/admin/master.blade.php ENDPATH**/ ?>