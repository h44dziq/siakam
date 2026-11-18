@extends('layouts.app')
@section('title', 'Profil')
@section('content')
<div class="head"><h1>Profil</h1></div>
<form class="panel pad" method="POST" action="{{ route('profil.update') }}" style="max-width:560px">
    @csrf @method('PUT')
    <div class="form-grid">
        <div class="field full">
            <label for="name">Nama</label>
            <input id="name" type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required>
        </div>
        <div class="field full">
            <label for="email">Email</label>
            <input id="email" type="email" value="{{ auth()->user()->email }}" disabled>
            <span class="hint">Email tidak bisa diubah setelah akun dibuat.</span>
        </div>
        <div class="field full"><h2 style="margin-top:8px">Ganti password</h2><span class="hint">Kosongkan jika tidak ingin mengganti.</span></div>
        <div class="field full">
            <label for="current_password">Password saat ini</label>
            <input id="current_password" type="password" name="current_password" autocomplete="current-password">
        </div>
        <div class="field">
            <label for="password">Password baru</label>
            <input id="password" type="password" name="password" autocomplete="new-password">
        </div>
        <div class="field">
            <label for="password_confirmation">Ulangi password baru</label>
            <input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password">
        </div>
    </div>
    <div class="actions" style="margin-top:24px"><button class="btn btn-primary">Simpan profil</button></div>
</form>
@endsection
