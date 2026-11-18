@extends('layouts.app')
@section('title', 'Data master')
@section('content')
<div class="head">
    <div>
        <h1>Data master</h1>
        <p>Perubahan di sini langsung muncul di form laporan. Data yang sudah dipakai laporan tidak bisa dihapus.</p>
    </div>
</div>
<div class="tabs">
    <a href="{{ route('admin.master') }}" class="{{ $tab === 'fasilitas' ? 'on' : '' }}">Gedung dan ruangan</a>
    <a href="{{ route('admin.master', ['tab' => 'kategori']) }}" class="{{ $tab === 'kategori' ? 'on' : '' }}">Kategori masalah</a>
</div>

@if ($tab === 'fasilitas')
    <form class="inline" method="POST" action="{{ route('admin.gedung.store') }}" style="max-width:460px; margin-bottom:20px">
        @csrf
        <input type="text" name="name" placeholder="Nama gedung baru" required maxlength="100" aria-label="Nama gedung baru">
        <button class="btn btn-primary">Tambah gedung</button>
    </form>
    <div class="panel">
        @forelse ($buildings as $b)
            <div class="bld">
                <div class="inline">
                    <form class="inline" style="flex:1" method="POST" action="{{ route('admin.gedung.update', $b) }}">
                        @csrf @method('PUT')
                        <input type="text" name="name" value="{{ $b->name }}" required maxlength="100" aria-label="Nama gedung" style="font-weight:600">
                        <button class="btn btn-sm">Simpan</button>
                    </form>
                    <form method="POST" action="{{ route('admin.gedung.destroy', $b) }}" onsubmit="return confirm('Hapus {{ e($b->name) }}?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger">Hapus</button>
                    </form>
                </div>
                <div class="rooms">
                    @foreach ($b->rooms as $r)
                        <div class="inline">
                            <form class="inline" style="flex:1" method="POST" action="{{ route('admin.ruangan.update', $r) }}">
                                @csrf @method('PUT')
                                <input type="text" name="name" value="{{ $r->name }}" required maxlength="100" aria-label="Nama ruangan">
                                <button class="btn btn-sm">Simpan</button>
                            </form>
                            <form method="POST" action="{{ route('admin.ruangan.destroy', $r) }}" onsubmit="return confirm('Hapus {{ e($r->name) }}?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </div>
                    @endforeach
                    <form class="inline" method="POST" action="{{ route('admin.ruangan.store', $b) }}" style="padding-left:16px">
                        @csrf
                        <input type="text" name="name" placeholder="Ruangan baru di {{ $b->name }}" required maxlength="100" aria-label="Ruangan baru">
                        <button class="btn btn-sm">Tambah ruangan</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="empty"><b>Belum ada gedung</b>Tambahkan gedung pertama, lalu ruangannya.</div>
        @endforelse
    </div>
@else
    <form class="inline" method="POST" action="{{ route('admin.kategori.store') }}" style="max-width:460px; margin-bottom:20px">
        @csrf
        <input type="text" name="name" placeholder="Nama kategori baru" required maxlength="100" aria-label="Nama kategori baru">
        <button class="btn btn-primary">Tambah kategori</button>
    </form>
    <div class="panel">
        @forelse ($categories as $c)
            <div class="bld">
                <div class="inline">
                    <form class="inline" style="flex:1" method="POST" action="{{ route('admin.kategori.update', $c) }}">
                        @csrf @method('PUT')
                        <input type="text" name="name" value="{{ $c->name }}" required maxlength="100" aria-label="Nama kategori">
                        <button class="btn btn-sm">Simpan</button>
                    </form>
                    <form method="POST" action="{{ route('admin.kategori.destroy', $c) }}" onsubmit="return confirm('Hapus {{ e($c->name) }}?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger">Hapus</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="empty"><b>Belum ada kategori</b>Tambahkan jenis masalah yang bisa dipilih mahasiswa.</div>
        @endforelse
    </div>
@endif
@endsection
