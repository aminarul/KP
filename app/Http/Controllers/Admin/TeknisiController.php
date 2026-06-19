<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Teknisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TeknisiController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        $teknisis = Teknisi::with('user')
            ->when($search, function ($query, $search) {
                return $query->where('kode_teknisi', 'like', "%{$search}%")
                             ->orWhere('wilayah', 'like', "%{$search}%")
                             ->orWhereHas('user', function ($q) use ($search) {
                                 $q->where('name', 'like', "%{$search}%")
                                   ->orWhere('email', 'like', "%{$search}%");
                             });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('admin.teknisi.index', compact('teknisis', 'search'));
    }

    public function create()
    {
        return view('admin.teknisi.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'kode_teknisi' => 'required|string|max:20|unique:teknisis,kode_teknisi',
            'alamat' => 'required|string',
            'wilayah' => 'required|string|max:100',
            'password' => 'required|min:6',
        ], [
            'kode_teknisi.unique' => 'Kode teknisi sudah digunakan.',
        ]);

        // Create user account
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'role' => 'teknisi',
            'is_active' => true,
        ]);

        // Create teknisi profile
        Teknisi::create([
            'user_id' => $user->id,
            'kode_teknisi' => $validated['kode_teknisi'],
            'alamat' => $validated['alamat'],
            'wilayah' => $validated['wilayah'],
        ]);

        return redirect()->route('admin.teknisi.index')
                         ->with('success', 'Teknisi berhasil ditambahkan! Akun login telah dibuat.');
    }

    public function edit($id)
    {
        $teknisi = Teknisi::with('user')->findOrFail($id);
        return view('admin.teknisi.edit', compact('teknisi'));
    }

    public function update(Request $request, $id)
    {
        $teknisi = Teknisi::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'kode_teknisi' => 'required|string|max:20|unique:teknisis,kode_teknisi,' . $id,
            'alamat' => 'required|string',
            'wilayah' => 'required|string|max:100',
        ]);

        // Update user
        $teknisi->user->update([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
        ]);

        // Update teknisi profile
        $teknisi->update([
            'kode_teknisi' => $validated['kode_teknisi'],
            'alamat' => $validated['alamat'],
            'wilayah' => $validated['wilayah'],
        ]);

        // Optional: update password if provided
        if ($request->filled('password')) {
            $request->validate(['password' => 'min:6']);
            $teknisi->user->update([
                'password' => Hash::make($request->password)
            ]);
        }

        return redirect()->route('admin.teknisi.index')
                         ->with('success', 'Data teknisi berhasil diupdate!');
    }

    public function destroy($id)
    {
        $teknisi = Teknisi::findOrFail($id);
        $user = $teknisi->user;
        
        $teknisi->delete();
        $user->delete();

        return redirect()->route('admin.teknisi.index')
                         ->with('success', 'Teknisi berhasil dihapus!');
    }
}