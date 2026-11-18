<?php $__env->startSection('title', 'Laporan masuk'); ?>
<?php $__env->startSection('content'); ?>
<div class="head">
    <div>
        <h1>Laporan masuk</h1>
        <p>Laporan yang perlu ditangani, lengkap dengan lokasi dan jenis masalahnya.</p>
    </div>
</div>
<form class="filters" method="GET">
    <div class="field">
        <label for="status">Status</label>
        <select id="status" name="status" onchange="this.form.submit()">
            <option value="aktif" <?php if($status === 'aktif'): echo 'selected'; endif; ?>>Perlu ditangani</option>
            <option value="reported" <?php if($status === 'reported'): echo 'selected'; endif; ?>>Dilaporkan</option>
            <option value="in_progress" <?php if($status === 'in_progress'): echo 'selected'; endif; ?>>Diproses</option>
            <option value="resolved" <?php if($status === 'resolved'): echo 'selected'; endif; ?>>Selesai</option>
            <option value="all" <?php if($status === 'all'): echo 'selected'; endif; ?>>Semua</option>
        </select>
    </div>
    <div class="field">
        <label for="building">Gedung</label>
        <select id="building" name="building" onchange="this.form.submit()">
            <option value="">Semua gedung</option>
            <?php $__currentLoopData = $buildings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($b->id); ?>" <?php if(request('building') == $b->id): echo 'selected'; endif; ?>><?php echo e($b->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
    <noscript><button class="btn">Terapkan</button></noscript>
</form>
<div class="panel">
    <?php echo $__env->make('partials.report-table', [
        'reports' => $reports,
        'showReporter' => false,
        'emptyTitle' => 'Tidak ada laporan di sini',
        'emptyText' => 'Coba ubah filter status atau gedung.',
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\hadziq\Documents\UAS\Sistem Pengaduan\siakam\resources\views/ob/dashboard.blade.php ENDPATH**/ ?>