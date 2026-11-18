<!DOCTYPE html>
<html lang="id">
<head>
<?php echo $__env->make('layouts.head', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</head>
<body>
<div class="guest-grid">
    <section class="guest-intro">
        <a class="brand" href="<?php echo e(route('login')); ?>"><span class="mark"></span>siakam</a>
        <div>
            <h1>Lapor kerusakan fasilitas, pantau sampai tuntas.</h1>
            <p class="lead">Satu tempat untuk laporan lab, ruang kelas, dan toilet di FIK. Petugas langsung tahu lokasinya, Anda tahu progresnya.</p>
            <ol class="flow">
                <li class="s-reported"><span class="track"><i class="f"></i><i></i><i></i></span><div><b>Dilaporkan</b><span>Pilih gedung, ruangan, dan kirim foto bukti.</span></div></li>
                <li class="s-in_progress"><span class="track"><i class="f"></i><i class="f"></i><i></i></span><div><b>Diproses</b><span>Petugas OB mulai menangani laporan.</span></div></li>
                <li class="s-resolved"><span class="track"><i class="f"></i><i class="f"></i><i class="f"></i></span><div><b>Selesai</b><span>Petugas mencatat tindakan yang dilakukan.</span></div></li>
            </ol>
        </div>
        <p class="fine small">Fakultas Ilmu Komputer</p>
    </section>
    <section class="guest-form">
        <div class="box">
            <?php echo $__env->yieldContent('content'); ?>
        </div>
    </section>
</div>
</body>
</html>
<?php /**PATH C:\Users\hadziq\Documents\UAS\Sistem Pengaduan\siakam\resources\views/layouts/guest.blade.php ENDPATH**/ ?>