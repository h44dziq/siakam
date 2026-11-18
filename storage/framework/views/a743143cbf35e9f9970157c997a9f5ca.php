<?php $__env->startSection('title', 'Ringkasan'); ?>
<?php $__env->startSection('content'); ?>
<?php
    $months = [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'];
    $exportQuery = array_filter(['month' => $f['month'], 'year' => $f['year']]);
    $max = max(1, $byCategory->max('c') ?? 1);
    $maxRoom = max(1, $topRooms->max('c') ?? 1);
?>
<div class="head">
    <div>
        <h1>Ringkasan fasilitas</h1>
        <p>Kondisi laporan <?php echo e($f['month'] ? $months[(int) $f['month']] : 'sepanjang tahun'); ?> <?php echo e($f['year'] ?: '(semua tahun)'); ?>.</p>
    </div>
    <a class="btn" href="<?php echo e(route('admin.laporan.export', $exportQuery)); ?>">Unduh CSV</a>
</div>

<form class="filters" method="GET">
    <div class="field">
        <label for="month">Bulan</label>
        <select id="month" name="month" onchange="this.form.submit()">
            <option value="">Semua bulan</option>
            <?php $__currentLoopData = $months; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($n); ?>" <?php if((int) $f['month'] === $n): echo 'selected'; endif; ?>><?php echo e($name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
    <div class="field">
        <label for="year">Tahun</label>
        <select id="year" name="year" onchange="this.form.submit()">
            <option value="">Semua tahun</option>
            <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $y): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($y); ?>" <?php if((string) $f['year'] === (string) $y): echo 'selected'; endif; ?>><?php echo e($y); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
    <noscript><button class="btn">Terapkan</button></noscript>
</form>

<div class="panel metrics">
    <div><b><?php echo e($total); ?></b><span>Laporan masuk</span></div>
    <div><b><?php echo e($byStatus['reported'] ?? 0); ?></b><span>Menunggu ditangani</span></div>
    <div><b><?php echo e($byStatus['in_progress'] ?? 0); ?></b><span>Sedang diproses</span></div>
    <div><b><?php echo e($byStatus['resolved'] ?? 0); ?></b><span>Selesai</span></div>
</div>

<div class="cols">
    <div class="panel">
        <div class="panel-head"><h2>Tiga ruangan paling sering dilaporkan</h2></div>
        <?php if($topRooms->isEmpty()): ?>
            <div class="empty">Belum ada laporan pada periode ini.</div>
        <?php else: ?>
        <ul class="bars">
            <?php $__currentLoopData = $topRooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li>
                    <div class="lbl"><b><?php echo e($t->room->name); ?></b><span><?php echo e($t->room->building->name); ?></span></div>
                    <div class="bar"><i style="width: <?php echo e(round($t->c / $maxRoom * 100)); ?>%"></i></div>
                    <div class="n"><?php echo e($t->c); ?></div>
                </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
        <?php endif; ?>
    </div>
    <div class="panel">
        <div class="panel-head"><h2>Laporan per jenis masalah</h2></div>
        <?php if($byCategory->isEmpty()): ?>
            <div class="empty">Belum ada laporan pada periode ini.</div>
        <?php else: ?>
        <ul class="bars">
            <?php $__currentLoopData = $byCategory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li>
                    <div class="lbl"><span style="color:var(--ink)"><?php echo e($c->category->name); ?></span></div>
                    <div class="bar"><i style="width: <?php echo e(round($c->c / $max * 100)); ?>%"></i></div>
                    <div class="n"><?php echo e($c->c); ?></div>
                </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\hadziq\Documents\UAS\Sistem Pengaduan\siakam\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>