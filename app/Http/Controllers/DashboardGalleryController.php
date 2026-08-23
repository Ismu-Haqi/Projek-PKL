<?php

namespace App\Http\Controllers;

use App\Models\DashboardGallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DashboardGalleryController extends Controller
{
    // ========================
    // INDEX (kelola galeri)
    // ========================
    public function index()
    {
        $images = DashboardGallery::urut()->get();
        return view('admin.galeri.index', compact('images'));
    }

    // ========================
    // STORE (upload gambar baru)
    // ========================
    public function store(Request $request)
    {
        $request->validate([
            'gambar'    => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
            'judul'     => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string|max:1000',
        ], [
            'gambar.required' => 'Pilih gambar yang akan diunggah.',
            'gambar.image'    => 'File harus berupa gambar.',
            'gambar.mimes'    => 'Format gambar harus JPG, JPEG, PNG, atau WEBP.',
            'gambar.max'      => 'Ukuran gambar maksimal 5MB.',
        ]);

        $path = $request->file('gambar')->store('dashboard-galeri', 'public');

        $urutanTerakhir = DashboardGallery::max('urutan') ?? 0;

        DashboardGallery::create([
            'gambar'        => $path,
            'judul'         => $request->judul,
            'deskripsi'     => $request->deskripsi,
            'urutan'        => $urutanTerakhir + 1,
            'aktif'         => true,
            'diunggah_oleh' => Auth::id(),
        ]);

        return back()->with('success', 'Gambar berhasil ditambahkan ke galeri dashboard.');
    }

    // ========================
    // UPDATE (edit judul/deskripsi/status aktif)
    // ========================
    public function update(Request $request, $id)
    {
        $image = DashboardGallery::findOrFail($id);

        $request->validate([
            'judul'     => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string|max:1000',
            'urutan'    => 'nullable|integer|min:0',
        ]);

        $image->update([
            'judul'     => $request->judul,
            'deskripsi' => $request->deskripsi,
            'urutan'    => $request->urutan ?? $image->urutan,
            'aktif'     => $request->has('aktif'),
        ]);

        return back()->with('success', 'Gambar galeri berhasil diperbarui.');
    }

    // ========================
    // DESTROY
    // ========================
    public function destroy($id)
    {
        $image = DashboardGallery::findOrFail($id);

        if ($image->gambar) {
            Storage::disk('public')->delete($image->gambar);
        }

        $image->delete();

        return back()->with('success', 'Gambar berhasil dihapus dari galeri.');
    }
}
