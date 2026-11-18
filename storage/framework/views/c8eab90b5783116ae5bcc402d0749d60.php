<?php $__env->startSection('title', 'Riwayat laporan'); ?>
<?php $__env->startSection('content'); ?>
<div class="head">
    <div>
        <h1>Riwayat laporan</h1>
        <p>Semua laporan yang pernah Anda kirim dan statusnya saat ini.</p>
    </div>
    <a class="btn btn-primary" href="<?php echo e(route('mahasiswa.laporan.create')); ?>">Buat laporan</a>
</div>
<div class="panel">
    <?php echo $__env->make('partials.report-table', [
        'reports' => $reports,
        'emptyTitle' => 'Belum ada laporan',
        'emptyText' => 'Lihat AC menyala di ruang kosong atau fasilitas rusak? Kirim laporan pertama Anda.',
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\hadziq\Documents\UAS\Sistem Pengaduan\siakam\resources\views/mahasiswa/dashboard.blade.php ENDPATH**/ ?>