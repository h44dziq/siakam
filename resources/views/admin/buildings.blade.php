@extends('layouts.app')
@section('title', 'Data Gedung')

@section('content')
<div class="page-head">
    <div>
        <h2 class="page-title">Data gedung</h2>
        <p class="page-sub">Gedung yang tersedia pada form laporan mahasiswa. Gedung yang sudah dipakai tidak bisa dihapus.</p>
    </div>
    <div class="page-actions">
        <a class="btn" href="{{ route('admin.ruangan.index') }}"><x-icon name="door" :size="16" /> Kelola ruangan</a>
    </div>
</div>

<form class="filters" method="POST" action="{{ route('admin.gedung.store') }}">
    @csrf
    <div class="filter-grid">
        <div class="field grow">
            <label for="new-building">Nama gedung baru</label>
            <input id="new-building" type="text" name="name" required maxlength="100" placeholder="Contoh: Gedung D">
        </div>
        <div class="filter-actions">
            <button class="btn btn-primary"><x-icon name="plus" :size="16" /> Tambah gedung</button>
        </div>
    </div>
</form>

<div class="card">
    <div class="card-head">
        <div>
            <h3 class="card-title">Daftar gedung</h3>
            <p class="card-sub">{{ $buildings->count() }} gedung terdaftar.</p>
        </div>
    </div>
    <div class="card-body">
        @if ($buildings->isEmpty())
            <x-empty-state title="Belum ada gedung" text="Tambahkan gedung pertama, lalu isi ruangannya." icon="building" />
        @else
            <div class="table-wrap">
                <table class="table">
                    <thead>
                        <tr><th>Nama gedung</th><th>Ruangan</th><th></th></tr>
                    </thead>
                    <tbody>
                        @foreach ($buildings as $building)
                            <tr>
                                <td>
                                    <form class="inline-form" method="POST" action="{{ route('admin.gedung.update', $building) }}">
                                        @csrf
                                        @method('PUT')
                                        <input type="text" name="name" value="{{ $building->name }}" required maxlength="100" aria-label="Nama gedung">
                                        <button class="btn btn-sm">Simpan</button>
                                    </form>
                                </td>
                                <td class="nowrap">
                                    <a class="link" href="{{ route('admin.ruangan.index', ['building_id' => $building->id]) }}">
                                        {{ $building->rooms_count }} ruangan
                                    </a>
                                </td>
                                <td class="num">
                                    <form method="POST" action="{{ route('admin.gedung.destroy', $building) }}"
                                          data-confirm="Hapus {{ $building->name }}?">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
