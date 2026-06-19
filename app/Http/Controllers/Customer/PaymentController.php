<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    public function index()
    {
        $customer = Auth::user()->customer;
        
        $payments = Payment::with(['booking.paket'])
            ->whereHas('booking', function ($query) use ($customer) {
                $query->where('customer_id', $customer->id);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        $totalUnpaid = Payment::whereHas('booking', function ($query) use ($customer) {
                $query->where('customer_id', $customer->id);
            })->where('status', 'unpaid')->sum('amount');
        
        $totalPaid = Payment::whereHas('booking', function ($query) use ($customer) {
                $query->where('customer_id', $customer->id);
            })->where('status', 'paid')->sum('amount');
        
        return view('customer.payment.index', compact('payments', 'totalUnpaid', 'totalPaid'));
    }

    public function uploadForm($id)
    {
        $payment = Payment::with('booking.paket')
            ->whereHas('booking', function ($query) {
                $query->where('customer_id', Auth::user()->customer->id);
            })
            ->findOrFail($id);
        
        if ($payment->status !== Payment::STATUS_UNPAID) {
            return redirect()->route('customer.payments.index')
                ->with('error', 'Pembayaran sudah diproses.');
        }
        
        return view('customer.payment.upload', compact('payment'));
    }

    public function upload(Request $request, $id)
    {
        $payment = Payment::with('booking')
            ->whereHas('booking', function ($query) {
                $query->where('customer_id', Auth::user()->customer->id);
            })
            ->findOrFail($id);
        
        if ($payment->status !== Payment::STATUS_UNPAID) {
            return back()->with('error', 'Pembayaran sudah diproses.');
        }
        
        $request->validate([
            'bukti_transfer' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'payment_method' => 'required|in:transfer,qris',
            'catatan' => 'nullable|string|max:500',
        ], [
            'bukti_transfer.required' => 'Bukti transfer wajib diupload.',
            'bukti_transfer.image' => 'File harus berupa gambar.',
            'payment_method.required' => 'Pilih metode pembayaran.',
        ]);
        
        // Upload bukti transfer
        if ($request->hasFile('bukti_transfer')) {
            $buktiPath = $request->file('bukti_transfer')->store('uploads/bukti_transfer', 'public');
            $payment->bukti_transfer = $buktiPath;
        }
        
        $payment->update([
            'payment_method' => $request->payment_method,
            'payment_date' => now(),
            'catatan' => $request->catatan,
            'status' => Payment::STATUS_PENDING,
        ]);
        
        return redirect()->route('customer.payments.index')
            ->with('success', 'Bukti transfer berhasil diupload! Menunggu verifikasi admin.');
    }
}