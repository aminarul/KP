<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status');
        
        $payments = Payment::with(['booking.customer.user', 'booking.paket'])
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        $statusCounts = [
            'unpaid' => Payment::where('status', 'unpaid')->count(),
            'pending' => Payment::where('status', 'pending')->count(),
            'paid' => Payment::where('status', 'paid')->count(),
            'failed' => Payment::where('status', 'failed')->count(),
        ];
        
        $totalRevenue = Payment::where('status', 'paid')->sum('amount');
        
        return view('admin.payment.index', compact('payments', 'statusCounts', 'totalRevenue', 'status'));
    }

    public function show($id)
    {
        $payment = Payment::with(['booking.customer.user', 'booking.paket', 'verifikator'])
            ->findOrFail($id);
        
        return view('admin.payment.show', compact('payment'));
    }

    public function verify($id)
    {
        $payment = Payment::findOrFail($id);
        
        if (!$payment->canBeVerified()) {
            return back()->with('error', 'Pembayaran tidak dapat diverifikasi.');
        }
        
        $payment->update([
            'status' => Payment::STATUS_PAID,
            'verified_at' => now(),
            'verified_by' => Auth::id(),
        ]);
        
        // Update booking status jika perlu
        $payment->booking->update([
            'status' => 'selesai'
        ]);
        
        return redirect()->route('admin.payments.index')
            ->with('success', 'Pembayaran berhasil diverifikasi!');
    }

    public function reject($id)
    {
        $payment = Payment::findOrFail($id);
        
        if ($payment->status !== Payment::STATUS_PENDING) {
            return back()->with('error', 'Pembayaran tidak dapat ditolak.');
        }
        
        $payment->update([
            'status' => Payment::STATUS_FAILED,
            'catatan' => request('catatan') ?? 'Pembayaran ditolak oleh admin',
        ]);
        
        return redirect()->route('admin.payments.index')
            ->with('warning', 'Pembayaran ditolak.');
    }

    public function generateInvoice($bookingId)
    {
        $booking = Booking::with('customer.user', 'paket')->findOrFail($bookingId);
        
        // Cek apakah sudah ada payment
        if ($booking->hasPayment()) {
            return redirect()->route('admin.payments.show', $booking->payment->id)
                ->with('info', 'Invoice sudah ada untuk booking ini.');
        }
        
        // Generate invoice number
        $invoiceNumber = 'INV-' . date('Ymd') . '-' . str_pad($booking->id, 4, '0', STR_PAD_LEFT);
        
        // Create payment
        $payment = Payment::create([
            'booking_id' => $booking->id,
            'invoice_number' => $invoiceNumber,
            'amount' => $booking->paket->harga,
            'status' => Payment::STATUS_UNPAID,
        ]);
        
        return redirect()->route('admin.payments.show', $payment->id)
            ->with('success', 'Invoice berhasil dibuat!');
    }
}