<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index(Request $request, string $tab = 'mahasiswa')
    {
        abort_unless(in_array($tab, ['mahasiswa', 'ob', 'admin']), 404);
        abort_if($tab === 'admin' && $request->user()->role !== 'superadmin', 403);

        $users = User::where('role', $tab)
            ->when($request->q, fn ($q, $v) => $q->where(fn ($w) => $w->where('name', 'like', "%{$v}%")->orWhere('email', 'like', "%{$v}%")->orWhere('nim', 'like', "%{$v}%")))
            ->orderBy('name')->paginate(15)->withQueryString();

        return view('admin.users', compact('users', 'tab'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'role' => ['required', Rule::in(['ob', 'admin'])],
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);
        abort_if($data['role'] === 'admin' && $request->user()->role !== 'superadmin', 403);

        User::create($data + ['is_active' => true]);

        return back()->with('ok', 'Akun dibuat.');
    }

    public function update(Request $request, User $user)
    {
        $this->authorizeTarget($request, $user);
        abort_if($user->role === 'mahasiswa', 403); // data mahasiswa tidak bisa diedit admin

        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'confirmed', Password::min(8)],
            'is_active' => ['required', 'boolean'],
        ]);

        if (empty($data['password'])) {
            unset($data['password']);
        }
        $user->update($data);

        return back()->with('ok', 'Akun diperbarui.');
    }

    public function toggle(Request $request, User $user)
    {
        $this->authorizeTarget($request, $user);
        $user->update(['is_active' => ! $user->is_active]);

        return back()->with('ok', $user->is_active ? 'Akun diaktifkan.' : 'Akun dinonaktifkan.');
    }

    /** Admin hanya mengelola mahasiswa & OB; akun admin hanya oleh superadmin; tidak ada aksi ke diri sendiri. */
    private function authorizeTarget(Request $request, User $target): void
    {
        $me = $request->user();
        abort_if($target->id === $me->id || $target->role === 'superadmin', 403);
        abort_if($target->role === 'admin' && $me->role !== 'superadmin', 403);
    }
}
