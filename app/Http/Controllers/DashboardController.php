<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PaketInternet;
use App\Models\Teknisi;
use App\Models\Customer;
use App\Models\CoverageArea;
use App\Models\Booking;

class DashboardController extends Controller
{
    public function adminDashboard()
    {
        $totalCustomers = User::where('role', 'customer')->count();
        $totalTeknisis = Teknisi::count();
        $totalPakets = PaketInternet::count();
        $totalCoverage = CoverageArea::count();
        
        // Hitung booking berdasarkan status
        $pendingBookings = Booking::where('status', 'pending')->count();
        $approvedBookings = Booking::where('status', 'approved')->count();
        $assignedBookings = Booking::where('status', 'assigned')->count();
        $onProgressBookings = Booking::where('status', 'on_progress')->count();
        $completedBookings = Booking::where('status', 'selesai')->count();
        $cancelledBookings = Booking::where('status', 'cancelled')->count();
        
        $activeBookings = $pendingBookings + $approvedBookings + $assignedBookings + $onProgressBookings;
        
        // Data untuk chart (nanti di step 6)
        $recentCustomers = User::where('role', 'customer')
            ->latest()
            ->take(5)
            ->get();
        
        $recentPakets = PaketInternet::latest()->take(5)->get();
        
        $recentBookings = Booking::with(['customer.user', 'paket'])
            ->latest()
            ->take(5)
            ->get();
        
        return view('dashboard.admin', compact(
            'totalCustomers', 
            'totalTeknisis', 
            'totalPakets',
            'totalCoverage',
            'activeBookings',
            'pendingBookings',
            'approvedBookings',
            'assignedBookings',
            'onProgressBookings',
            'completedBookings',
            'cancelledBookings',
            'recentCustomers',
            'recentPakets',
            'recentBookings'
        ));
    }
    public function customerDashboard()
    {
        $user = auth()->user();
        
        // Ambil data customer
        $customer = $user->customer;
        
        if (!$customer) {
            // Jika customer belum memiliki profile, buatkan
            $customer = Customer::create([
                'user_id' => $user->id,
                'nik' => '',
                'alamat' => '',
            ]);
        }
        
        // Ambil booking milik customer ini
        $myBookings = Booking::with(['paket', 'teknisi.user'])
            ->where('customer_id', $customer->id)
            ->latest()
            ->take(5)
            ->get();
        
        // Hitung statistik booking
        $totalBookings = Booking::where('customer_id', $customer->id)->count();
        $pendingBookings = Booking::where('customer_id', $customer->id)
            ->whereIn('status', ['pending', 'approved', 'assigned', 'on_progress'])
            ->count();
        
        // Hitung total tagihan yang belum dibayar
        $totalUnpaid = \App\Models\Payment::whereHas('booking', function ($query) use ($customer) {
            $query->where('customer_id', $customer->id);
        })->where('status', 'unpaid')->sum('amount');
        
        $totalUnpaidFormatted = 'Rp ' . number_format($totalUnpaid, 0, ',', '.');
        
        return view('dashboard.customer', compact(
            'user', 
            'customer',
            'myBookings',
            'totalBookings',
            'pendingBookings',
            'totalUnpaidFormatted'
        ));
    }

    public function teknisiDashboard()
    {
        $user = auth()->user();
        $teknisi = $user->teknisi;
        
        if (!$teknisi) {
            $statistics = [
                'total' => 0,
                'assigned' => 0,
                'on_progress' => 0,
                'completed' => 0
            ];
            $assignedTasks = collect();
            $progressTasks = collect();
            $completedTasks = collect();
        } else {
            $assignedTasks = Booking::with(['customer.user', 'paket'])
                ->where('teknisi_id', $teknisi->id)
                ->where('status', 'assigned')
                ->orderBy('tanggal_booking', 'asc')
                ->get();

            $progressTasks = Booking::with(['customer.user', 'paket'])
                ->where('teknisi_id', $teknisi->id)
                ->where('status', 'on_progress')
                ->orderBy('tanggal_mulai', 'desc')
                ->get();

            $completedTasks = Booking::with(['customer.user', 'paket'])
                ->where('teknisi_id', $teknisi->id)
                ->where('status', 'selesai')
                ->orderBy('tanggal_selesai', 'desc')
                ->take(10)
                ->get();

            $statistics = [
                'total' => Booking::where('teknisi_id', $teknisi->id)->count(),
                'assigned' => $assignedTasks->count(),
                'on_progress' => $progressTasks->count(),
                'completed' => $completedTasks->count(),
            ];
        }
        
        return view('dashboard.teknisi', compact(
            'user',
            'teknisi',
            'statistics',
            'assignedTasks',
            'progressTasks',
            'completedTasks'
        ));
    }
}