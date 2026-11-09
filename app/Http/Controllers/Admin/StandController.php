<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Stand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StandController extends Controller
{
    /**
     * Display a listing of stands.
     */
    public function index()
    {
        $stands = Stand::withCount('products')->orderBy('id', 'asc')->get();
        return view('admin.stands.index', compact('stands'));
    }

    /**
     * Show form for creating a new stand.
     */
    public function create()
    {
        return view('admin.stands.create');
    }

    /**
     * Store a newly created stand.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'department' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'image_url' => ['nullable', 'string', 'max:500'],
            'status' => ['required', 'in:open,closed'],
            'location' => ['nullable', 'string', 'max:255'],
            'contact_person' => ['nullable', 'string', 'max:255'],
        ]);

        $imagePath = $validated['image_url'] ?? null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('stands', 'public');
            $imagePath = Storage::url($imagePath);
        }

        Stand::create([
            'name' => $validated['name'],
            'department' => $validated['department'],
            'description' => $validated['description'] ?? null,
            'image' => $imagePath,
            'status' => $validated['status'],
            'location' => $validated['location'] ?? null,
            'contact_person' => $validated['contact_person'] ?? null,
        ]);

        return redirect()->route('admin.stands.index')
            ->with('success', 'Stand "' . $validated['name'] . '" berhasil ditambahkan!');
    }

    /**
     * Show form for editing the stand.
     */
    public function edit(Stand $stand)
    {
        return view('admin.stands.edit', compact('stand'));
    }

    /**
     * Update the stand in storage.
     */
    public function update(Request $request, Stand $stand)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'department' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'image_url' => ['nullable', 'string', 'max:500'],
            'status' => ['required', 'in:open,closed'],
            'location' => ['nullable', 'string', 'max:255'],
            'contact_person' => ['nullable', 'string', 'max:255'],
        ]);

        $imagePath = $stand->image;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('stands', 'public');
            $imagePath = Storage::url($imagePath);
        } elseif (!empty($validated['image_url'])) {
            $imagePath = $validated['image_url'];
        }

        $stand->update([
            'name' => $validated['name'],
            'department' => $validated['department'],
            'description' => $validated['description'] ?? null,
            'image' => $imagePath,
            'status' => $validated['status'],
            'location' => $validated['location'] ?? null,
            'contact_person' => $validated['contact_person'] ?? null,
        ]);

        return redirect()->route('admin.stands.index')
            ->with('success', 'Data Stand "' . $stand->name . '" berhasil diperbarui!');
    }

    /**
     * Toggle status open / closed.
     */
    public function toggleStatus(Stand $stand)
    {
        $newStatus = ($stand->status === 'open') ? 'closed' : 'open';
        $stand->update(['status' => $newStatus]);

        $statusText = ($newStatus === 'open') ? 'DIBUKA' : 'DITUTUP';
        return back()->with('success', 'Status ' . $stand->name . ' sekarang ' . $statusText . '.');
    }

    /**
     * Remove the stand.
     */
    public function destroy(Stand $stand)
    {
        $name = $stand->name;
        $stand->delete();

        return redirect()->route('admin.stands.index')
            ->with('info', 'Stand "' . $name . '" telah berhasil dihapus.');
    }
}

