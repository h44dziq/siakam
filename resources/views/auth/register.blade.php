@extends('layouts.guest')
@section('title', 'Daftar')

@section('content')
<h2>Daftar sebagai mahasiswa</h2>
<p>Gunakan email kampus. Email tidak bisa diubah setelah akun dibuat.</p>

@if ($errors->any())
    <div class="alert alert-err" role="alert">
        <ul>
            @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('register') }}">
    @csrf
    <div class="field">
        <label for="username">Username</label>
        <input id="username" type="text" name="username" value="{{ old('username') }}" required autofocus
               autocomplete="username" maxlength="50" placeholder="Nama tampilan Anda">
    </div>
    <div class="field">
        <label for="email">Email kampus</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email"
               placeholder="{{ '2310512001@'.config('siakam.campus_email_domain') }}">
        <span class="field-hint">Gunakan email mahasiswa resmi UPN Veteran Jakarta.</span>
    </div>
    <div class="field">
        <label for="password">Password</label>
        <input id="password" type="password" name="password" required autocomplete="new-password">
        <span class="field-hint">Minimal 8 karakter.</span>
    </div>
    <div class="field">
        <label for="password_confirmation">Ulangi password</label>
        <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password">
    </div>
    <button class="btn btn-primary btn-block">Buat akun</button>
</form>

<p class="auth-switch">Sudah punya akun? <a href="{{ route('login') }}">Masuk</a></p>
@endsection
