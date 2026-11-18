<span class="status s-<?php echo e($report->status); ?>">
    <span class="track" aria-hidden="true"><?php for($i = 1; $i <= 3; $i++): ?><i class="<?php echo e($i <= $report->step() ? 'f' : ''); ?>"></i><?php endfor; ?></span>
    <?php echo e($report->label()); ?>

</span>
<?php /**PATH C:\Users\hadziq\Documents\UAS\Sistem Pengaduan\siakam\resources\views/partials/status.blade.php ENDPATH**/ ?>