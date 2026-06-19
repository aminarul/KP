<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CoverageArea;
use Illuminate\Http\Request;

class CoverageAreaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        $areas = CoverageArea::when($search, function ($query, $search) {
            return $query->where('nama_wilayah', 'like', "%{$search}%")
                         ->orWhere('kode_pos', 'like', "%{$search}%");
        })->orderBy('nama_wilayah')->paginate(10);
        
        return view('admin.coverage.index', compact('areas', 'search'));
    }

    public function create()
    {
        return view('admin.coverage.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_wilayah' => 'required|string|max:100|unique:coverage_areas,nama_wilayah',
            'kode_pos' => 'nullable|string|max:10',
            'keterangan' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        CoverageArea::create($validated);

        return redirect()->route('admin.coverage.index')
                         ->with('success', 'Wilayah layanan berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $area = CoverageArea::findOrFail($id);
        return view('admin.coverage.edit', compact('area'));
    }

    public function update(Request $request, $id)
    {
        $area = CoverageArea::findOrFail($id);
        
        $validated = $request->validate([
            'nama_wilayah' => 'required|string|max:100|unique:coverage_areas,nama_wilayah,' . $id,
            'kode_pos' => 'nullable|string|max:10',
            'keterangan' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $area->update($validated);

        return redirect()->route('admin.coverage.index')
                         ->with('success', 'Wilayah layanan berhasil diupdate!');
    }

    public function destroy($id)
    {
        $area = CoverageArea::findOrFail($id);
        $area->delete();

        return redirect()->route('admin.coverage.index')
                         ->with('success', 'Wilayah layanan berhasil dihapus!');
    }

    public function toggleStatus($id)
    {
        $area = CoverageArea::findOrFail($id);
        $area->update(['is_active' => !$area->is_active]);
        
        $status = $area->is_active ? 'diaktifkan' : 'dinonaktifkan';
        
        return back()->with('success', "Wilayah {$status}!");
    }
}