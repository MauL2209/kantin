<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Stand;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Guard against removing the last admin from the system.
     *
     * @param  User         $user      The user being modified
     * @param  string|null  $newRole   The new role being assigned (null = deletion/deactivation)
     * @throws \Exception
     */
    private function guardLastAdmin(User $user, ?string $newRole = null): void
    {
        // Only relevant when the user being modified is currently an admin
        if ($user->role !== 'admin') {
            return;
        }

        // If the new role is still 'admin', there is no risk
        if ($newRole !== null && $newRole === 'admin') {
            return;
        }

        $adminCount = User::where('role', 'admin')->count();
        if ($adminCount <= 1) {
            throw new \Exception('Tidak dapat mengubah role, menonaktifkan, atau menghapus Admin terakhir yang tersisa.');
        }
    }

    /**
     * Display a listing of all users.
     */
    public function index(Request $request)
    {
        $query = User::with('stand')->latest();

        // Search by name, email, or NIS
        if ($request->filled('q')) {
            $keyword = $request->q;
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('email', 'like', "%{$keyword}%")
                  ->orWhere('nis', 'like', "%{$keyword}%");
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $stands = Stand::all();

        return view('admin.users.create', compact('stands'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role'     => ['required', 'in:siswa,penjaga,admin'],
            'stand_id' => ['required_if:role,penjaga', 'nullable', 'exists:stands,id'],
            'nis'        => ['nullable', 'string', 'max:50'],
            'class_room' => ['nullable', 'string', 'max:50'],
            'phone'      => ['nullable', 'string', 'max:20'],
        ]);

        // Invariant (AC 1.6): stand_id must be null for non-penjaga roles
        $standId = ($validated['role'] === 'penjaga') ? ($validated['stand_id'] ?? null) : null;

        User::create([
            'name'       => $validated['name'],
            'email'      => $validated['email'],
            'password'   => Hash::make($validated['password']),
            'role'       => $validated['role'],
            'stand_id'   => $standId,
            'nis'        => $validated['nis'] ?? null,
            'class_room' => $validated['class_room'] ?? null,
            'phone'      => $validated['phone'] ?? null,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Akun "' . $validated['name'] . '" berhasil dibuat!');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $stands = Stand::all();

        return view('admin.users.edit', compact('user', 'stands'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        // Guard: prevent removing the last admin
        try {
            $this->guardLastAdmin($user, $request->role);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }

        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'role'     => ['required', 'in:siswa,penjaga,admin'],
            'stand_id' => ['required_if:role,penjaga', 'nullable', 'exists:stands,id'],
            'nis'        => ['nullable', 'string', 'max:50'],
            'class_room' => ['nullable', 'string', 'max:50'],
            'phone'      => ['nullable', 'string', 'max:20'],
        ]);

        // Invariant (AC 1.6): stand_id must always be null for non-penjaga roles,
        // regardless of whether the role changed or stayed the same.
        $standId = ($validated['role'] === 'penjaga') ? ($validated['stand_id'] ?? null) : null;

        $data = [
            'name'       => $validated['name'],
            'email'      => $validated['email'],
            'role'       => $validated['role'],
            'stand_id'   => $standId,
            'nis'        => $validated['nis'] ?? null,
            'class_room' => $validated['class_room'] ?? null,
            'phone'      => $validated['phone'] ?? null,
        ];

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'Akun "' . $user->name . '" berhasil diperbarui!');
    }

    /**
     * Reset the password for the specified user.
     * Admin does not need to know the current password (AC 2.6).
     */
    public function resetPassword(Request $request, User $user)
    {
        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Password akun "' . $user->name . '" berhasil direset!');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        // Guard: prevent deleting the last admin
        try {
            $this->guardLastAdmin($user, null);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }

        $name = $user->name;
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('info', 'Akun "' . $name . '" telah dihapus.');
    }
}
