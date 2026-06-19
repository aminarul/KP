<?php

namespace App\Http\Controllers\Teknisi;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Notifications\InstallationCompletedNotification;
use App\Notifications\InstallationCompletedByTeknisiNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $teknisi = $user->teknisi;
        
        if (!$teknisi) {
            $statistics = ['total' => 0, 'assigned' => 0, 'on_progress' => 0, 'completed' => 0];
            $assignedTasks = collect();
            $progressTasks = collect();
            $completedTasks = collect();
        } else {
            $assignedTasks = Booking::with(['customer.user', 'paket'])
                ->where('teknisi_id', $teknisi->id)
                ->where('status', 'on_progress')
                ->where('payment_status', 'paid')
                ->orderBy('tanggal_booking', 'asc')
                ->get();

            $progressTasks = collect(); // Tidak ada lagi progress terpisah
            
            $completedTasks = Booking::with(['customer.user', 'paket'])
                ->where('teknisi_id', $teknisi->id)
                ->where('status', 'selesai')
                ->orderBy('updated_at', 'desc')
                ->take(10)
                ->get();

            $statistics = [
                'total' => Booking::where('teknisi_id', $teknisi->id)->count(),
                'assigned' => $assignedTasks->count(),
                'on_progress' => 0,
                'completed' => $completedTasks->count(),
            ];
        }
        
        return view('admin.teknisi.tasks.index', compact('assignedTasks', 'progressTasks', 'completedTasks', 'statistics'));
    }

    public function show($id)
    {
        $user = Auth::user();
        $teknisi = $user->teknisi;
        
        $task = Booking::with(['customer.user', 'paket', 'customer'])
            ->where('teknisi_id', $teknisi->id)
            ->findOrFail($id);
        
        return view('admin.teknisi.tasks.show', compact('task'));
    }

    public function startTask($id)
    {
        $user = Auth::user();
        $teknisi = $user->teknisi;
        
        $task = Booking::where('teknisi_id', $teknisi->id)
            ->where('status', 'on_progress')
            ->findOrFail($id);
        
        // Task sudah dalam status on_progress, langsung ke halaman detail
        return redirect()->route('teknisi.tasks.show', $task->id)
            ->with('success', 'Silakan lakukan pemasangan dan upload data modem.');
    }

    public function completeTask(Request $request, $id)
    {
        $user = Auth::user();
        $teknisi = $user->teknisi;
        
        $task = Booking::where('teknisi_id', $teknisi->id)
            ->where('status', 'on_progress')
            ->findOrFail($id);
        
        $request->validate([
            'foto_modem' => 'required|image|mimes:jpg,jpeg,png|max:5120',
            'type_modem' => 'required|string|max:100',
            'sn_ont' => 'required|string|max:50',
            'keterangan_pemasangan' => 'nullable|string',
        ], [
            'foto_modem.required' => 'Foto modem wajib diupload.',
            'foto_modem.image' => 'File harus berupa gambar.',
            'type_modem.required' => 'Type / merk modem wajib diisi.',
            'sn_ont.required' => 'Serial number ONT wajib diisi.',
        ]);
        
        // Upload foto modem
        if ($request->hasFile('foto_modem')) {
            // Hapus file lama jika ada
            if ($task->foto_modem && Storage::disk('public')->exists($task->foto_modem)) {
                Storage::disk('public')->delete($task->foto_modem);
            }
            
            $fotoPath = $request->file('foto_modem')->store('uploads/modems', 'public');
            $task->foto_modem = $fotoPath;
        }
        
        $task->type_modem = $request->type_modem;
        $task->sn_ont = $request->sn_ont;
        $task->keterangan_pemasangan = $request->keterangan_pemasangan;
        $task->status = 'selesai';
        $task->completed_at = now();
        $task->save();
        
        // ✅ KIRIM NOTIFIKASI KE CUSTOMER
        try {
            if ($task->customer && $task->customer->user) {
                $task->customer->user->notify(new InstallationCompletedNotification($task));
            }
        } catch (\Exception $e) {
            Log::error('Gagal mengirim notifikasi ke customer: ' . $e->getMessage());
        }
        
        // ✅ KIRIM NOTIFIKASI KE ADMIN (Pemasangan Selesai oleh Teknisi)
        try {
            $admins = \App\Models\User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new InstallationCompletedByTeknisiNotification($task));
            }
        } catch (\Exception $e) {
            Log::error('Gagal mengirim notifikasi ke admin: ' . $e->getMessage());
        }
        
        return redirect()->route('teknisi.tasks.index')
            ->with('success', 'Pemasangan selesai! Data modem telah tersimpan. Customer dan admin telah mendapatkan notifikasi.');
    }

    public function cancelTask($id)
    {
        $user = Auth::user();
        $teknisi = $user->teknisi;
        
        $task = Booking::where('teknisi_id', $teknisi->id)
            ->where('status', 'on_progress')
            ->findOrFail($id);
        
        $task->update([
            'status' => 'approved',
            'teknisi_id' => null,
        ]);
        
        return redirect()->route('teknisi.tasks.index')
            ->with('warning', 'Tugas telah dikembalikan ke admin.');
    }
}