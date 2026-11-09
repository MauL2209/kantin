<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    public function showChangeForm()
    {
        // AC 8.1: Only siswa and penjaga can access the change password page
        if (Auth::user()->isAdmin()) {
            abort(403, 'Halaman ubah password tidak tersedia untuk Admin.');
        }

        return view('profile.change-password');
    }

    public function change(Request $request)
    {
        // AC 8.1: Only siswa and penjaga can access the change password page
        if (Auth::user()->isAdmin()) {
            abort(403, 'Halaman ubah password tidak tersedia untuk Admin.');
        }

        $request->validate([
            'current_password'      => ['required'],
            'new_password'          => ['required', 'string', 'min:8', 'different:current_password'],
            'new_password_confirmation' => ['required', 'same:new_password'],
        ]);

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors([
                'current_password' => 'Password lama tidak sesuai.',
            ]);
        }

        Auth::user()->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect()->route('profile.show')
            ->with('success', 'Password berhasil diubah.');
    }
}
