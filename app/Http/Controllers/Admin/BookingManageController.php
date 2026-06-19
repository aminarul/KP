<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Teknisi;
use App\Notifications\BookingApprovedNotification;
use App\Notifications\PaymentConfirmedNotification;
use App\Notifications\NewPaymentNotification;
use App\Notifications\TeknisiAssignedNotification;
use App\Notifications\NewTaskNotification; // Tambahkan ini
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingManageController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status');
        $paymentStatus = $request->get('payment_status');
        
        $bookings = Booking::with(['customer.user', 'paket', 'teknisi.user'])
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($paymentStatus, function ($query, $paymentStatus) {
                return $query->where('payment_status', $paymentStatus);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        $statusCounts = [
            'pending' => Booking::where('status', 'pending')->count(),
            'approved' => Booking::where('status', 'approved')->count(),
            'on_progress' => Booking::where('status', 'on_progress')->count(),
            'selesai' => Booking::where('status', 'selesai')->count(),
        ];
        
        $paymentCounts = [
            'unpaid' => Booking::where('payment_status', 'unpaid')->count(),
            'pending' => Booking::where('payment_status', 'pending')->count(),
            'paid' => Booking::where('payment_status', 'paid')->count(),
        ];
        
        return view('admin.booking.index', compact('bookings', 'statusCounts', 'paymentCounts', 'status'));
    }

    public function show($id)
    {
        $booking = Booking::with(['customer.user', 'paket', 'teknisi.user', 'confirmer'])
            ->findOrFail($id);
        
        $teknisis = Teknisi::with('user')->get();
        
        return view('admin.booking.show', compact('booking', 'teknisis'));
    }

    public function approve($id)
    {
        $booking = Booking::findOrFail($id);
        
        if ($booking->status !== 'pending') {
            return back()->with('error', 'Booking tidak dapat disetujui.');
        }
        
        if (!$booking->invoice_number) {
            $booking->generateInvoiceNumber();
        }
        
        $booking->update([
            'status' => 'approved'
        ]);
        
        // ✅ KIRIM NOTIFIKASI KE CUSTOMER
        try {
            $booking->customer->user->notify(new BookingApprovedNotification($booking));
        } catch (\Exception $e) {
            \Log::error('Gagal mengirim notifikasi booking approved: ' . $e->getMessage());
        }
        
        return back()->with('success', 'Booking disetujui! Silakan minta customer untuk membayar.');
    }

    public function confirmPayment($id)
    {
        $booking = Booking::findOrFail($id);
        
        if ($booking->payment_status !== 'pending') {
            return back()->with('error', 'Tidak ada pembayaran yang menunggu verifikasi.');
        }
        
        $booking->update([
            'payment_status' => 'paid',
            'paid_at' => now(),
            'confirmed_by' => Auth::id(),
        ]);
        
        // ✅ KIRIM NOTIFIKASI KE CUSTOMER
        try {
            $booking->customer->user->notify(new PaymentConfirmedNotification($booking));
        } catch (\Exception $e) {
            \Log::error('Gagal mengirim notifikasi payment confirmed: ' . $e->getMessage());
        }
        
        return back()->with('success', 'Pembayaran dikonfirmasi! Sekarang assign teknisi.');
    }

    public function rejectPayment($id)
    {
        $booking = Booking::findOrFail($id);
        
        if ($booking->payment_status !== 'pending') {
            return back()->with('error', 'Tidak ada pembayaran yang dapat ditolak.');
        }
        
        $booking->update([
            'payment_status' => 'unpaid',
            'bukti_transfer' => null,
        ]);
        
        // Kirim notifikasi bahwa pembayaran ditolak
        try {
            $booking->customer->user->notify(new \App\Notifications\PaymentRejectedNotification($booking));
        } catch (\Exception $e) {
            \Log::error('Gagal mengirim notifikasi payment rejected: ' . $e->getMessage());
        }
        
        return back()->with('warning', 'Pembayaran ditolak. Minta customer upload ulang.');
    }

    public function assignTeknisi(Request $request, $id)
    {
        $request->validate([
            'teknisi_id' => 'required|exists:teknisis,id',
        ]);
        
        $booking = Booking::findOrFail($id);
        
        if ($booking->status !== 'approved' || $booking->payment_status !== 'paid') {
            return back()->with('error', 'Booking harus approved dan pembayaran lunas.');
        }
        
        $teknisi = Teknisi::find($request->teknisi_id);
        
        $booking->update([
            'teknisi_id' => $request->teknisi_id,
            'status' => 'on_progress',
        ]);
        
        // ✅ KIRIM NOTIFIKASI KE CUSTOMER
        try {
            $booking->customer->user->notify(new TeknisiAssignedNotification($booking));
        } catch (\Exception $e) {
            \Log::error('Gagal mengirim notifikasi teknisi assigned ke customer: ' . $e->getMessage());
        }
        
        // ✅ KIRIM NOTIFIKASI KE TEKNISI (Tugas Baru)
        try {
            if ($teknisi && $teknisi->user) {
                $teknisi->user->notify(new NewTaskNotification($booking));
            }
        } catch (\Exception $e) {
            \Log::error('Gagal mengirim notifikasi new task ke teknisi: ' . $e->getMessage());
        }
        
        return redirect()->route('admin.bookings.show', $booking->id)
            ->with('success', 'Teknisi berhasil diassign.');
    }

    public function reject($id)
    {
        $booking = Booking::findOrFail($id);
        
        if ($booking->status !== 'pending') {
            return back()->with('error', 'Booking tidak dapat ditolak.');
        }
        
        $booking->update(['status' => 'cancelled']);
        
        // Kirim notifikasi bahwa booking ditolak
        try {
            $booking->customer->user->notify(new \App\Notifications\BookingRejectedNotification($booking));
        } catch (\Exception $e) {
            \Log::error('Gagal mengirim notifikasi booking rejected: ' . $e->getMessage());
        }
        
        return back()->with('success', 'Booking ditolak.');
    }
}