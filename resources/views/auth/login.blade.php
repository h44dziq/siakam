@extends('layouts.guest')
@section('title', 'Masuk')
@section('content')
<h2>Masuk ke SIAKAM</h2>
<p class="muted">Gunakan email kampus untuk mahasiswa, atau akun dari admin untuk petugas.</p>
<form method="POST" action="{{ route('login') }}">
    @csrf
    @if ($errors->any())<div class="flash err" style="margin:0" role="alert">{{ $errors->first() }}</div>@endif
    <div class="field">
        <label for="email">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
    </div>
    <div class="field">
        <label for="password">Password</label>
        <input id="password" type="password" name="password" required autocomplete="current-password">
    </div>
    <label class="check"><input type="checkbox" name="remember" value="1"> Ingat saya di perangkat ini</label>
    <button class="btn btn-primary">Masuk</button>
</form>
<p class="alt">Mahasiswa belum punya akun? <a href="{{ route('register') }}">Daftar</a></p>
@endsection
