@extends('layouts.app')
@section('title', 'Buat Laporan')

@section('content')
<div class="page-head">
    <div>
        <h2 class="page-title">Buat laporan fasilitas</h2>
        <p class="page-sub">Semakin jelas lokasi dan deskripsinya, semakin cepat petugas menemukan masalahnya.</p>
    </div>
    <div class="page-actions">
        <a class="btn" href="{{ route('mahasiswa.riwayat') }}">Riwayat laporan</a>
    </div>
</div>

<div class="split">
    <form class="card" method="POST" action="{{ route('mahasiswa.laporan.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="card-head">
            <div>
                <h3 class="card-title">Detail masalah</h3>
                <p class="card-sub">Kolom bertanda wajib harus diisi.</p>
            </div>
        </div>
        <div class="card-body">
            <div class="form-grid">
                <div class="field">
                    <label for="building_id">Gedung</label>
                    <select id="building_id" name="building_id" data-building required>
                        <option value="">Pilih gedung</option>
                        @foreach ($buildings as $building)
                            <option value="{{ $building->id }}" @selected(old('building_id') == $building->id)>{{ $building->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label for="room_id">Ruangan</label>
                    <select id="room_id" name="room_id" data-room required disabled
                            data-rooms="{{ json_encode($roomsByBuilding) }}" data-selected="{{ old('room_id') }}">
                        <option value="">Pilih gedung dulu</option>
                    </select>
                </div>
                <div class="field full">
                    <label for="category_id">Jenis masalah</label>
                    <select id="category_id" name="category_id" required>
                        <option value="">Pilih jenis masalah</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field full">
                    <label for="description">Apa yang terjadi?</label>
                    <textarea id="description" name="description" required minlength="10" maxlength="1000"
                              placeholder="Contoh: AC di barisan depan menyala sejak pagi padahal ruangan kosong.">{{ old('description') }}</textarea>
                    <span class="field-hint">Minimal 10 karakter. Sebutkan lokasi detail bila perlu.</span>
                </div>
                <div class="field full">
                    <label for="photo">Foto bukti <span class="muted">(opsional)</span></label>
                    <input id="photo" type="file" name="photo" accept="image/*" data-photo-input>
                    <span class="field-hint">JPG atau PNG, maksimal 4 MB.</span>
                    <div class="photo-preview" data-photo-preview>
                        <span><x-icon name="camera" :size="22" /> Pratinjau foto akan muncul di sini.</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-foot">
            <div class="page-actions">
                <button class="btn btn-primary"><x-icon name="check" :size="16" /> Kirim laporan</button>
                <a class="btn btn-ghost" href="{{ route('mahasiswa.dashboard') }}">Batal</a>
            </div>
        </div>
    </form>

    <div class="stack">
        <div class="card">
            <div class="card-head"><h3 class="card-title">Agar cepat ditangani</h3></div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item is-info">
                        <span class="timeline-dot"></span>
                        <div class="timeline-body">
                            <b>Pilih lokasi yang tepat</b>
                            <div class="timeline-meta">Gedung dan ruangan menentukan petugas yang datang.</div>
                        </div>
                    </div>
                    <div class="timeline-item is-warn">
                        <span class="timeline-dot"></span>
                        <div class="timeline-body">
                            <b>Jelaskan masalahnya</b>
                            <div class="timeline-meta">Sebutkan objek yang rusak dan sejak kapan.</div>
                        </div>
                    </div>
                    <div class="timeline-item is-success">
                        <span class="timeline-dot"></span>
                        <div class="timeline-body">
                            <b>Lampirkan foto</b>
                            <div class="timeline-meta">Foto mempercepat verifikasi laporan Anda.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-head"><h3 class="card-title">Setelah dikirim</h3></div>
            <div class="card-body">
                <p class="muted small">Laporan masuk ke admin dengan status <b>Dilaporkan</b>. Anda dapat memantau perubahan status dan catatan penanganan pada halaman detail laporan.</p>
            </div>
        </div>
    </div>
</div>
@endsection
