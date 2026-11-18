<?php $__env->startSection('title', 'Buat laporan'); ?>
<?php $__env->startSection('content'); ?>
<div class="head">
    <div>
        <h1>Buat laporan</h1>
        <p>Semakin jelas lokasi dan deskripsinya, semakin cepat petugas menemukan masalahnya.</p>
    </div>
</div>
<form class="panel pad" method="POST" action="<?php echo e(route('mahasiswa.laporan.store')); ?>" enctype="multipart/form-data" style="max-width:760px">
    <?php echo csrf_field(); ?>
    <div class="form-grid">
        <div class="field">
            <label for="building_id">Gedung</label>
            <select id="building_id" name="building_id" data-building required>
                <option value="">Pilih gedung</option>
                <?php $__currentLoopData = $buildings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($b->id); ?>" <?php if(old('building_id') == $b->id): echo 'selected'; endif; ?>><?php echo e($b->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="field">
            <label for="room_id">Ruangan</label>
            <select id="room_id" name="room_id" data-room data-rooms="<?php echo e(json_encode($roomsByBuilding)); ?>" data-selected="<?php echo e(old('room_id')); ?>" required disabled>
                <option value="">Pilih gedung dulu</option>
            </select>
        </div>
        <div class="field full">
            <label for="category_id">Jenis masalah</label>
            <select id="category_id" name="category_id" required>
                <option value="">Pilih jenis masalah</option>
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($c->id); ?>" <?php if(old('category_id') == $c->id): echo 'selected'; endif; ?>><?php echo e($c->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="field full">
            <label for="description">Apa yang terjadi?</label>
            <textarea id="description" name="description" required minlength="10" maxlength="1000" placeholder="Contoh: AC di barisan depan menyala sejak pagi, ruangan kosong."><?php echo e(old('description')); ?></textarea>
        </div>
        <div class="field full">
            <label for="photo">Foto bukti (opsional)</label>
            <input id="photo" type="file" name="photo" accept="image/*">
            <span class="hint">JPG atau PNG, maksimal 4 MB.</span>
        </div>
    </div>
    <div class="actions" style="margin-top:24px">
        <button class="btn btn-primary">Kirim laporan</button>
        <a class="btn" href="<?php echo e(route('mahasiswa.dashboard')); ?>">Batal</a>
    </div>
</form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\hadziq\Documents\UAS\Sistem Pengaduan\siakam\resources\views/reports/create.blade.php ENDPATH**/ ?>