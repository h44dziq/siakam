@extends('layouts.guest')
@section('title', 'Masuk')

@section('content')
<h2>Selamat datang kembali</h2>
<p>Masuk untuk memantau laporan fasilitas kampus.</p>

@if ($errors->any())
    <div class="alert alert-err" role="alert">{{ $errors->first() }}</div>
@endif

<form method="POST" action="{{ route('login') }}">
    @csrf
    <div class="field">
        <label for="email">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
               autocomplete="username" placeholder="nama@mahasiswa.upnvj.ac.id">
    </div>
    <div class="field">
        <label for="password">Password</label>
        <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="••••••••">
    </div>
    <label class="check"><input type="checkbox" name="remember" value="1"> Ingat saya di perangkat ini</label>
    <button class="btn btn-primary btn-block">Masuk</button>
</form>

<p class="auth-switch">Mahasiswa belum punya akun? <a href="{{ route('register') }}">Daftar sekarang</a></p>

<div class="auth-note">
    <b>Akun demo</b> — password <span class="mono">password</span><br>
    Pemimpin: <span class="mono">pemimpin@siakam.test</span><br>
    Admin: <span class="mono">admin@siakam.test</span><br>
    Mahasiswa: <span class="mono">2310512001@mahasiswa.upnvj.ac.id</span>
</div>
@endsection
