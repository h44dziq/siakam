<?php $__env->startSection('title', 'Laporan'); ?>
<?php $__env->startSection('content'); ?>
<div class="head">
    <div>
        <h1>Semua laporan</h1>
        <p>Pantau seluruh laporan dari mahasiswa, lalu buka detailnya untuk meninjau atau mengubah status.</p>
    </div>
    <a class="btn" href="<?php echo e(route('admin.laporan.export', request()->only(['q', 'status', 'from', 'to']))); ?>">Unduh CSV</a>
</div>
<form class="filters" method="GET">
    <div class="field grow">
        <label for="q">Cari pelapor</label>
        <input id="q" type="text" name="q" value="<?php echo e(request('q')); ?>" placeholder="Nama atau NIM">
    </div>
    <div class="field">
        <label for="status">Status</label>
        <select id="status" name="status">
            <option value="">Semua status</option>
            <?php $__currentLoopData = \App\Models\Report::LABELS; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($k); ?>" <?php if(request('status') === $k): echo 'selected'; endif; ?>><?php echo e($l); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
    <div class="field"><label for="from">Dari tanggal</label><input id="from" type="date" name="from" value="<?php echo e(request('from')); ?>"></div>
    <div class="field"><label for="to">Sampai tanggal</label><input id="to" type="date" name="to" value="<?php echo e(request('to')); ?>"></div>
    <button class="btn btn-primary">Terapkan</button>
    <?php if(request()->hasAny(['q', 'status', 'from', 'to'])): ?><a class="btn" href="<?php echo e(route('admin.laporan.index')); ?>">Reset</a><?php endif; ?>
</form>
<div class="panel">
    <?php echo $__env->make('partials.report-table', [
        'reports' => $reports,
        'showReporter' => true,
        'emptyTitle' => 'Tidak ada laporan yang cocok',
        'emptyText' => 'Ubah kata kunci atau rentang tanggalnya.',
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\hadziq\Documents\UAS\Sistem Pengaduan\siakam\resources\views/admin/reports.blade.php ENDPATH**/ ?>