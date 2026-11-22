@extends('layouts.app')
@section('title', 'Kategori Fasilitas')

@section('content')
<div class="page-head">
    <div>
        <h2 class="page-title">Kategori fasilitas</h2>
        <p class="page-sub">Jenis masalah yang bisa dipilih mahasiswa saat membuat laporan.</p>
    </div>
</div>

<form class="filters" method="POST" action="{{ route('admin.kategori.store') }}">
    @csrf
    <div class="filter-grid">
        <div class="field grow">
            <label for="new-category">Nama kategori baru</label>
            <input id="new-category" type="text" name="name" required maxlength="100" placeholder="Contoh: Kebocoran atap">
        </div>
        <div class="filter-actions">
            <button class="btn btn-primary"><x-icon name="plus" :size="16" /> Tambah kategori</button>
        </div>
    </div>
</form>

<div class="card">
    <div class="card-head">
        <div>
            <h3 class="card-title">Daftar kategori</h3>
            <p class="card-sub">{{ $categories->count() }} kategori terdaftar.</p>
        </div>
    </div>
    <div class="card-body">
        @if ($categories->isEmpty())
            <x-empty-state title="Belum ada kategori" text="Tambahkan jenis masalah agar mahasiswa bisa melapor." icon="tag" />
        @else
            <div class="table-wrap">
                <table class="table">
                    <thead>
                        <tr><th>Nama kategori</th><th>Dipakai</th><th></th></tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                            <tr>
                                <td>
                                    <form class="inline-form" method="POST" action="{{ route('admin.kategori.update', $category) }}">
                                        @csrf
                                        @method('PUT')
                                        <input type="text" name="name" value="{{ $category->name }}" required maxlength="100" aria-label="Nama kategori">
                                        <button class="btn btn-sm">Simpan</button>
                                    </form>
                                </td>
                                <td class="nowrap">{{ $category->reports_count }} laporan</td>
                                <td class="num">
                                    <form method="POST" action="{{ route('admin.kategori.destroy', $category) }}"
                                          data-confirm="Hapus kategori {{ $category->name }}?">
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
