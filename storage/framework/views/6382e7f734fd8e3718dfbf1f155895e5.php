<?php ($showReporter = $showReporter ?? false); ?>
<div class="tbl-wrap">
<?php if($reports->isEmpty()): ?>
    <div class="empty">
        <b><?php echo e($emptyTitle ?? 'Belum ada laporan'); ?></b>
        <?php echo e($emptyText ?? ''); ?>

    </div>
<?php else: ?>
    <table class="tbl">
        <thead>
            <tr>
                <th>ID</th>
                <th>Waktu</th>
                <th>Lokasi</th>
                <th>Masalah</th>
                <?php if($showReporter): ?><th>Pelapor</th><?php endif; ?>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php $__currentLoopData = $reports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td class="muted">#<?php echo e($r->id); ?></td>
                <td><?php echo e($r->created_at->translatedFormat('d M Y')); ?><br><span class="muted small"><?php echo e($r->created_at->format('H:i')); ?></span></td>
                <td class="loc"><b><?php echo e($r->room->name); ?></b><span><?php echo e($r->room->building->name); ?></span></td>
                <td><?php echo e($r->category->name); ?></td>
                <?php if($showReporter): ?><td><?php echo e($r->user->name); ?><br><span class="muted small"><?php echo e($r->user->nim); ?></span></td><?php endif; ?>
                <td><?php echo $__env->make('partials.status', ['report' => $r], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></td>
                <td class="num"><a href="<?php echo e(route('laporan.show', $r)); ?>">Lihat detail</a></td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
<?php endif; ?>
</div>
<?php if($reports->hasPages()): ?>
    <div class="pager"><?php echo e($reports->links()); ?></div>
<?php endif; ?>
<?php /**PATH C:\Users\hadziq\Documents\UAS\Sistem Pengaduan\siakam\resources\views/partials/report-table.blade.php ENDPATH**/ ?>