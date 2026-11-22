@extends('layouts.app')
@section('title', 'Data Ruangan')

@section('content')
<div class="page-head">
    <div>
        <h2 class="page-title">Data ruangan</h2>
        <p class="page-sub">Ruangan terhubung ke gedung dan dipakai pada form laporan. Ruangan yang sudah dipakai tidak bisa dihapus.</p>
    </div>
    <div class="page-actions">
        <a class="btn" href="{{ route('admin.gedung.index') }}"><x-icon name="building" :size="16" /> Kelola gedung</a>
    </div>
</div>

<form class="filters" method="GET">
    <div class="filter-grid">
        <div class="field">
            <label for="building_id">Filter gedung</label>
            <select id="building_id" name="building_id" data-auto>
                <option value="">Semua gedung</option>
                @foreach ($buildings as $building)
                    <option value="{{ $building->id }}" @selected($buildingId == $building->id)>{{ $building->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="filter-actions">
            <noscript><button class="btn">Terapkan</button></noscript>
            @if ($buildingId)<a class="btn btn-ghost" href="{{ route('admin.ruangan.index') }}">Reset</a>@endif
        </div>
    </div>
</form>

<form class="filters" method="POST" action="{{ route('admin.ruangan.store') }}">
    @csrf
    <div class="filter-grid">
        <div class="field">
            <label for="new-building">Gedung</label>
            <select id="new-building" name="building_id" required>
                <option value="">Pilih gedung</option>
                @foreach ($buildings as $building)
                    <option value="{{ $building->id }}" @selected($buildingId == $building->id)>{{ $building->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="field grow">
            <label for="new-room">Nama ruangan baru</label>
            <input id="new-room" type="text" name="name" required maxlength="100" placeholder="Contoh: Lab Multimedia">
        </div>
        <div class="filter-actions">
            <button class="btn btn-primary"><x-icon name="plus" :size="16" /> Tambah ruangan</button>
        </div>
    </div>
</form>

<div class="card">
    <div class="card-head">
        <div>
            <h3 class="card-title">Daftar ruangan</h3>
            <p class="card-sub">{{ $rooms->count() }} ruangan ditampilkan.</p>
        </div>
    </div>
    <div class="card-body">
        @if ($rooms->isEmpty())
            <x-empty-state title="Belum ada ruangan" text="Tambahkan gedung lalu isi ruangannya di halaman ini." icon="door" />
        @else
            <div class="table-wrap">
                <table class="table">
                    <thead>
                        <tr><th>Ruangan dan gedung</th><th>Laporan</th><th></th></tr>
                    </thead>
                    <tbody>
                        @foreach ($rooms as $room)
                            <tr>
                                <td>
                                    <form class="inline-form" method="POST" action="{{ route('admin.ruangan.update', $room) }}">
                                        @csrf
                                        @method('PUT')
                                        <input type="text" name="name" value="{{ $room->name }}" required maxlength="100" aria-label="Nama ruangan">
                                        <select name="building_id" aria-label="Gedung">
                                            @foreach ($buildings as $building)
                                                <option value="{{ $building->id }}" @selected($room->building_id === $building->id)>{{ $building->name }}</option>
                                            @endforeach
                                        </select>
                                        <button class="btn btn-sm">Simpan</button>
                                    </form>
                                </td>
                                <td class="nowrap">{{ $room->reports_count }} laporan</td>
                                <td class="num">
                                    <form method="POST" action="{{ route('admin.ruangan.destroy', $room) }}"
                                          data-confirm="Hapus ruangan {{ $room->name }}?">
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
