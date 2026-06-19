<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaketInternet;
use Illuminate\Http\Request;

class PaketInternetController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        $pakets = PaketInternet::when($search, function ($query, $search) {
            return $query->where('nama_paket', 'like', "%{$search}%")
                         ->orWhere('speed', 'like', "%{$search}%");
        })->orderBy('harga', 'asc')->paginate(10);
        
        return view('admin.paket.index', compact('pakets', 'search'));
    }

    public function create()
    {
        return view('admin.paket.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_paket' => 'required|string|max:100|unique:paket_internets,nama_paket',
            'speed' => 'required|string|max:50',
            'harga' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:aktif,nonaktif',
        ], [
            'nama_paket.required' => 'Nama paket wajib diisi.',
            'nama_paket.unique' => 'Nama paket sudah ada.',
            'speed.required' => 'Kecepatan internet wajib diisi.',
            'harga.required' => 'Harga wajib diisi.',
            'harga.numeric' => 'Harga harus berupa angka.',
        ]);

        PaketInternet::create($validated);

        return redirect()->route('admin.paket.index')
                         ->with('success', 'Paket Internet berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $paket = PaketInternet::findOrFail($id);
        return view('admin.paket.edit', compact('paket'));
    }

    public function update(Request $request, $id)
    {
        $paket = PaketInternet::findOrFail($id);
        
        $validated = $request->validate([
            'nama_paket' => 'required|string|max:100|unique:paket_internets,nama_paket,' . $id,
            'speed' => 'required|string|max:50',
            'harga' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $paket->update($validated);

        return redirect()->route('admin.paket.index')
                         ->with('success', 'Paket Internet berhasil diupdate!');
    }

    public function destroy($id)
    {
        $paket = PaketInternet::findOrFail($id);
        $paket->delete();

        return redirect()->route('admin.paket.index')
                         ->with('success', 'Paket Internet berhasil dihapus!');
    }
}