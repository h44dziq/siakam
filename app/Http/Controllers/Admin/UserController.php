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
        abort_unless(in_array($tab, ['mahasiswa', 'admin']), 404);

        $users = User::where('role', $tab)
            ->when($request->q, fn ($q, $v) => $q->where(fn ($w) => $w
                ->where('name', 'like', "%{$v}%")
                ->orWhere('email', 'like', "%{$v}%")
                ->orWhere('nim', 'like', "%{$v}%")))
            ->orderBy('name')->paginate(12)->withQueryString();

        return view('admin.users', [
            'users' => $users,
            'tab' => $tab,
            'counts' => User::selectRaw('role, count(*) as total')->groupBy('role')->pluck('total', 'role'),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'role' => ['required', Rule::in(['admin'])],
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        User::create($data + ['is_active' => true]);

        return back()->with('ok', 'Akun admin dibuat.');
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

    /** Admin mengelola mahasiswa & admin lain; akun pemimpin dan diri sendiri tidak bisa diubah. */
    private function authorizeTarget(Request $request, User $target): void
    {
        abort_if($target->id === $request->user()->id, 403);
        abort_if($target->role === 'pemimpin', 403);
    }
}
