<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Show the authenticated user's profile page.
     */
    public function show()
    {
        $user = Auth::user()->load('stand');
        return view('profile.show', compact('user'));
    }

    /**
     * Update the authenticated user's profile.
     *
     * AC 7.9 — Partial save: non-avatar fields (name, phone, nis, class_room)
     * are validated and saved first. The avatar is validated separately; if it
     * fails validation the non-avatar fields are ALREADY saved and the user is
     * redirected with a warning flash plus the avatar validation errors.
     *
     * NEVER updates: role, id, stand_id, email, password — those fields are
     * excluded from $rules and never placed into the $data array.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // Step 1: Validate non-avatar fields (AC 7.9 — save these first)
        $rules = [
            'name'   => ['required', 'string', 'max:255'],
            'phone'  => ['nullable', 'digits_between:10,15'],
        ];

        if ($user->isSiswa()) {
            $rules['nis']        = ['nullable', 'string', 'max:50'];
            $rules['class_room'] = ['nullable', 'string', 'max:50'];
        }

        $validated = $request->validate($rules);

        // Build update payload — never include role, id, stand_id, email, password
        $data = [
            'name'  => $validated['name'],
            'phone' => $validated['phone'] ?? null,
        ];

        if ($user->isSiswa()) {
            $data['nis']        = $validated['nis'] ?? null;
            $data['class_room'] = $validated['class_room'] ?? null;
        }

        // Save non-avatar fields immediately (AC 7.9)
        $user->update($data);

        // Step 2: Process avatar separately (AC 7.9 — partial save)
        if ($request->hasFile('avatar')) {
            $avatarValidator = \Illuminate\Support\Facades\Validator::make(
                $request->all(),
                ['avatar' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048']]
            );

            if ($avatarValidator->fails()) {
                // Non-avatar fields already saved — only avatar failed
                return redirect()->route('profile.show')
                    ->withErrors($avatarValidator)
                    ->with('warning', 'Profil berhasil diperbarui, namun avatar gagal diunggah.');
            }

            // Avatar is valid — save it
            $avatarsDir = public_path('avatars');
            if (!is_dir($avatarsDir)) {
                mkdir($avatarsDir, 0755, true);
            }

            // Remove old avatar if exists
            if ($user->avatar) {
                $oldPath = $avatarsDir . DIRECTORY_SEPARATOR . $user->avatar;
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            $avatarName = $user->id . '_' . time() . '.' . $request->file('avatar')->getClientOriginalExtension();
            $request->file('avatar')->move($avatarsDir, $avatarName);
            $user->update(['avatar' => $avatarName]);
        }

        return redirect()->route('profile.show')
            ->with('success', 'Profil berhasil diperbarui.');
    }
}
