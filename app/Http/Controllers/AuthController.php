<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $cred = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $cred['email'])->first();
        if ($user && ! $user->is_active && Hash::check($cred['password'], $user->password)) {
            return back()->withErrors(['email' => 'Akun Anda dinonaktifkan. Hubungi admin.'])->onlyInput('email');
        }

        if (! Auth::attempt($cred, $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'Email atau password salah.'])->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->route(Auth::user()->homeRoute());
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $domain = strtolower(config('siakam.campus_email_domain'));

        $data = $request->validate([
            'username' => ['required', 'string', 'max:50', 'unique:users,username'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email', function ($attr, $value, $fail) use ($domain) {
                if (! str_ends_with(strtolower($value), '@'.$domain)) {
                    $fail("Gunakan email kampus berakhiran @{$domain}.");
                }
            }],
            'password' => ['required', 'min:8', 'confirmed'],
        ], [
            'password.confirmed' => 'Konfirmasi password tidak sama.',
        ]);

        // NIM diambil dari bagian depan email kampus bila berupa angka
        $local = strstr($data['email'], '@', true);

        $user = User::create([
            'name' => $data['username'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => $data['password'],
            'role' => 'mahasiswa',
            'nim' => ctype_digit($local) ? $local : null,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('mahasiswa.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
