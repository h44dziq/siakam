@extends('layouts.guest')
@section('title', 'Daftar')
@section('content')
<h2>Daftar sebagai mahasiswa</h2>
<p class="muted">Akun mahasiswa memakai email kampus dan emailnya tidak bisa diubah setelah dibuat.</p>
<form method="POST" action="{{ route('register') }}">
    @csrf
    <div class="field">
        <label for="username">Username</label>
        <input id="username" type="text" name="username" value="{{ old('username') }}" required autofocus autocomplete="username">
        @error('username')<span class="err-text">{{ $message }}</span>@enderror
    </div>
    <div class="field">
        <label for="email">Email kampus</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="{{ '2310512001@'.config('siakam.campus_email_domain') }}">
        @error('email')<span class="err-text">{{ $message }}</span>@enderror
    </div>
    <div class="field">
        <label for="password">Password</label>
        <input id="password" type="password" name="password" required autocomplete="new-password">
        <span class="hint">Minimal 8 karakter.</span>
        @error('password')<span class="err-text">{{ $message }}</span>@enderror
    </div>
    <div class="field">
        <label for="password_confirmation">Ulangi password</label>
        <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password">
    </div>
    <button class="btn btn-primary">Buat akun</button>
</form>
<p class="alt">Sudah punya akun? <a href="{{ route('login') }}">Masuk</a></p>
@endsection
