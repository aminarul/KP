<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\PaketInternet;
use App\Models\Customer;
use App\Notifications\NewPaymentNotification;
use App\Notifications\NewBookingNotification; // Tambahkan use ini
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    public function index()
    {
        $customer = Auth::user()->customer;
        
        if (!$customer) {
            return redirect()->route('customer.dashboard')->with('error', 'Data customer tidak lengkap. Silakan hubungi admin.');
        }
        
        $bookings = Booking::with(['paket', 'teknisi.user'])
            ->where('customer_id', $customer->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('customer.booking.index', compact('bookings'));
    }

    public function create()
    {
        $pakets = PaketInternet::where('status', 'aktif')->get();
        $customer = Auth::user()->customer;
        
        if (!$customer) {
            // Create customer record if not exists
            $customer = Customer::create([
                'user_id' => Auth::id(),
                'nik' => '',
                'alamat' => '',
            ]);
        }
        
        return view('customer.booking.create', compact('pakets', 'customer'));
    }

    public function store(Request $request)
    {
        $customer = Auth::user()->customer;
        
        if (!$customer) {
            $customer = Customer::create([
                'user_id' => Auth::id(),
                'nik' => '',
                'alamat' => $request->alamat_pasang,
            ]);
        }
        
        $validated = $request->validate([
            'paket_id' => 'required|exists:paket_internets,id',
            'tanggal_booking' => 'required|date|after_or_equal:today',
            'alamat_pasang' => 'required|string|min:10',
            'maps_link' => 'nullable|url',
            'catatan' => 'nullable|string|max:500',
            'foto_ktp' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'foto_rumah' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Handle file uploads
        if ($request->hasFile('foto_ktp')) {
            $fotoKtpPath = $request->file('foto_ktp')->store('uploads/ktp', 'public');
            $customer->foto_ktp = $fotoKtpPath;
        }

        if ($request->hasFile('foto_rumah')) {
            $fotoRumahPath = $request->file('foto_rumah')->store('uploads/rumah', 'public');
            $customer->foto_rumah = $fotoRumahPath;
        }
        
        // Update alamat customer jika berbeda
        if ($customer->alamat != $validated['alamat_pasang']) {
            $customer->alamat = $validated['alamat_pasang'];
        }
        
        $customer->save();

        // Create booking
        $booking = Booking::create([
            'customer_id' => $customer->id,
            'paket_id' => $validated['paket_id'],
            'tanggal_booking' => $validated['tanggal_booking'],
            'alamat_pasang' => $validated['alamat_pasang'],
            'maps_link' => $validated['maps_link'] ?? null,
            'catatan' => $validated['catatan'] ?? null,
            'status' => Booking::STATUS_PENDING,
        ]);

        // ✅ KIRIM NOTIFIKASI KE ADMIN (Booking Baru)
        try {
            $admins = \App\Models\User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new NewBookingNotification($booking));
            }
        } catch (\Exception $e) {
            Log::error('Gagal mengirim notifikasi booking baru ke admin: ' . $e->getMessage());
        }

        return redirect()->route('customer.booking.success', $booking->id)
                         ->with('success', 'Booking berhasil dibuat! Menunggu persetujuan admin.');
    }

    public function show($id)
    {
        $customer = Auth::user()->customer;
        
        if (!$customer) {
            return redirect()->route('customer.dashboard')->with('error', 'Data customer tidak ditemukan.');
        }
        
        $booking = Booking::with(['paket', 'teknisi.user'])
            ->where('customer_id', $customer->id)
            ->findOrFail($id);
        
        return view('customer.booking.show', compact('booking'));
    }

    public function success($id)
    {
        $booking = Booking::with('paket')->findOrFail($id);
        
        return view('customer.booking.success', compact('booking'));
    }

    public function cancel($id)
    {
        $customer = Auth::user()->customer;
        $booking = Booking::where('customer_id', $customer->id)
            ->whereIn('status', [Booking::STATUS_PENDING, Booking::STATUS_APPROVED])
            ->findOrFail($id);
        
        $booking->update(['status' => Booking::STATUS_CANCELLED]);
        
        return redirect()->route('customer.booking.index')
                         ->with('success', 'Booking berhasil dibatalkan.');
    }

    public function paymentForm($id)
    {
        $customer = Auth::user()->customer;
        $booking = Booking::with('paket')
            ->where('customer_id', $customer->id)
            ->where('status', 'approved')
            ->findOrFail($id);
        
        // Generate invoice jika belum ada
        if (!$booking->invoice_number) {
            $booking->generateInvoiceNumber();
        }
        
        return view('customer.booking.payment', compact('booking'));
    }

    public function uploadPayment(Request $request, $id)
    {
        $customer = Auth::user()->customer;
        $booking = Booking::with('paket')
            ->where('customer_id', $customer->id)
            ->where('status', 'approved')
            ->where('payment_status', 'unpaid')
            ->findOrFail($id);
        
        $request->validate([
            'bukti_transfer' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'bukti_transfer.required' => 'Bukti transfer wajib diupload.',
            'bukti_transfer.image' => 'File harus berupa gambar.',
            'bukti_transfer.max' => 'Ukuran file maksimal 2MB.',
        ]);
        
        // Upload bukti transfer
        if ($request->hasFile('bukti_transfer')) {
            // Hapus file lama jika ada
            if ($booking->bukti_transfer && Storage::disk('public')->exists($booking->bukti_transfer)) {
                Storage::disk('public')->delete($booking->bukti_transfer);
            }
            
            $buktiPath = $request->file('bukti_transfer')->store('uploads/bukti_transfer', 'public');
            $booking->bukti_transfer = $buktiPath;
        }
        
        $booking->payment_status = 'pending';
        $booking->save();
        
        // ✅ KIRIM NOTIFIKASI KE ADMIN (Pembayaran Baru)
        try {
            $admins = \App\Models\User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new NewPaymentNotification($booking));
            }
        } catch (\Exception $e) {
            Log::error('Gagal mengirim notifikasi pembayaran ke admin: ' . $e->getMessage());
        }
        
        return redirect()->route('customer.booking.show', $booking->id)
            ->with('success', 'Bukti pembayaran berhasil diupload! Menunggu verifikasi admin.');
    }
}