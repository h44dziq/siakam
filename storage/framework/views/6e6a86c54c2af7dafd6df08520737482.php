<?php $__env->startSection('title', 'Laporan #'.$report->id); ?>
<?php $__env->startSection('content'); ?>
<?php
    $u = auth()->user();
    $back = match (true) {
        $u->role === 'mahasiswa' => route('mahasiswa.dashboard'),
        $u->role === 'ob' => route('ob.dashboard'),
        default => route('admin.laporan.index'),
    };
    $next = $report->nextStatus();
    $canUpdate = in_array($u->role, ['ob', 'admin', 'superadmin']) && $next;
?>
<div class="head">
    <div>
        <a class="small" href="<?php echo e($back); ?>">Kembali ke daftar</a>
        <h1 style="margin-top:6px"><?php echo e($report->category->name); ?></h1>
        <p>Laporan #<?php echo e($report->id); ?>, dikirim <?php echo e($report->created_at->translatedFormat('d F Y, H:i')); ?></p>
    </div>
    <?php echo $__env->make('partials.status', ['report' => $report], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</div>

<div class="detail">
    <div class="stack">
        <div class="panel pad">
            <dl class="facts">
                <div><dt>Gedung</dt><dd><?php echo e($report->room->building->name); ?></dd></div>
                <div><dt>Ruangan</dt><dd><?php echo e($report->room->name); ?></dd></div>
                <div><dt>Pelapor</dt><dd><?php echo e($report->user->name); ?><?php if($report->user->nim): ?> <span class="muted" style="font-weight:400">(<?php echo e($report->user->nim); ?>)</span><?php endif; ?></dd></div>
                <div><dt>Jenis masalah</dt><dd><?php echo e($report->category->name); ?></dd></div>
            </dl>
            <p style="margin-top:20px; white-space:pre-line"><?php echo e($report->description); ?></p>
            <?php if($report->photo_path): ?>
                <img class="photo" style="margin-top:20px" src="<?php echo e(route('foto.show', $report)); ?>" alt="Foto bukti laporan #<?php echo e($report->id); ?>">
            <?php endif; ?>
        </div>

        <?php if($canUpdate): ?>
            <form class="panel pad" method="POST" action="<?php echo e(route('laporan.status', $report)); ?>">
                <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                <input type="hidden" name="status" value="<?php echo e($next); ?>">
                <h2 style="margin-bottom:14px"><?php echo e($next === 'in_progress' ? 'Mulai tangani laporan ini' : 'Tandai laporan selesai'); ?></h2>
                <div class="field">
                    <label for="note">Catatan penanganan (opsional)</label>
                    <textarea id="note" name="note" style="min-height:80px" maxlength="500" placeholder="<?php echo e($next === 'in_progress' ? 'Contoh: menuju lokasi sekarang.' : 'Contoh: kabel dipasang ulang, proyektor sudah menyala.'); ?>"></textarea>
                    <span class="hint">Status hanya bisa maju dan tidak bisa dikembalikan.</span>
                </div>
                <div class="actions" style="margin-top:16px">
                    <button class="btn btn-primary"><?php echo e($next === 'in_progress' ? 'Ubah ke Diproses' : 'Ubah ke Selesai'); ?></button>
                </div>
            </form>
        <?php endif; ?>
    </div>

    <div class="stack">
        <div class="panel pad s-<?php echo e($report->status); ?>">
            <div class="stepper">
                <?php $__currentLoopData = \App\Models\Report::LABELS; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="<?php echo e($loop->iteration <= $report->step() ? 'f' : ''); ?> s-<?php echo e($key); ?>"><b><?php echo e($label); ?></b></div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
        <div class="panel pad">
            <h2 style="margin-bottom:18px">Riwayat penanganan</h2>
            <ul class="log">
                <?php $__currentLoopData = $report->histories->reverse(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="s-<?php echo e($h->to_status); ?>">
                        <b><?php echo e(\App\Models\Report::LABELS[$h->to_status] ?? $h->to_status); ?></b>
                        <div class="muted small"><?php echo e($h->author->name); ?>, <?php echo e($h->created_at->translatedFormat('d M Y, H:i')); ?></div>
                        <?php if($h->note): ?><div class="note"><?php echo e($h->note); ?></div><?php endif; ?>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\hadziq\Documents\UAS\Sistem Pengaduan\siakam\resources\views/reports/show.blade.php ENDPATH**/ ?>