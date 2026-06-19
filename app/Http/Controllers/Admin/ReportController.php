<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use App\Models\PaketInternet;
use App\Models\Teknisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        // ==========================================
        // HITUNG PENDAPATAN YANG BENAR
        // ==========================================
        
        // Total pendapatan dari booking yang sudah LUNAS (payment_status = 'paid')
        $totalRevenue = Booking::where('payment_status', 'paid')
            ->with('paket')
            ->get()
            ->sum(function ($booking) {
                return $booking->paket ? $booking->paket->harga : 0;
            });
        
        // Total booking
        $totalBookings = Booking::count();
        $pendingBookings = Booking::where('status', 'pending')->count();
        $approvedBookings = Booking::where('status', 'approved')->count();
        $onProgressBookings = Booking::where('status', 'on_progress')->count();
        $completedBookings = Booking::where('status', 'selesai')->count();
        
        // Chart data: Pendapatan per bulan (6 bulan terakhir)
        $monthlyRevenue = [];
        $monthlyBookings = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthName = $month->format('M Y');
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();
            
            // Revenue bulan ini (hanya yang status paid)
            $revenue = Booking::where('payment_status', 'paid')
                ->whereBetween('paid_at', [$monthStart, $monthEnd])
                ->with('paket')
                ->get()
                ->sum(function ($booking) {
                    return $booking->paket ? $booking->paket->harga : 0;
                });
            
            // Booking bulan ini (semua status)
            $bookings = Booking::whereBetween('created_at', [$monthStart, $monthEnd])->count();
            
            $monthlyRevenue[] = [
                'month' => $monthName,
                'revenue' => $revenue,
            ];
            
            $monthlyBookings[] = [
                'month' => $monthName,
                'count' => $bookings,
            ];
        }
        
        // Chart data: Status booking
        $statusData = [
            ['status' => 'Pending', 'count' => $pendingBookings],
            ['status' => 'Disetujui', 'count' => $approvedBookings],
            ['status' => 'Pemasangan', 'count' => $onProgressBookings],
            ['status' => 'Selesai', 'count' => $completedBookings],
        ];
        
        // Paket terlaris
        $topPackages = PaketInternet::withCount('bookings')
            ->orderBy('bookings_count', 'desc')
            ->take(5)
            ->get();
        
        // Customer aktif
        $activeCustomers = User::where('role', 'customer')
            ->whereHas('customer.bookings', function ($query) {
                $query->where('status', 'selesai');
            })
            ->count();
        
        // Teknisi teraktif
        $topTeknisis = Teknisi::withCount('bookings')
            ->orderBy('bookings_count', 'desc')
            ->take(5)
            ->get();
        
        // Pendapatan bulan ini
        $thisMonthRevenue = Booking::where('payment_status', 'paid')
            ->whereMonth('paid_at', now()->month)
            ->whereYear('paid_at', now()->year)
            ->with('paket')
            ->get()
            ->sum(function ($booking) {
                return $booking->paket ? $booking->paket->harga : 0;
            });
        
        return view('admin.report.index', compact(
            'totalRevenue',
            'totalBookings',
            'pendingBookings',
            'approvedBookings',
            'onProgressBookings',
            'completedBookings',
            'monthlyRevenue',
            'monthlyBookings',
            'statusData',
            'topPackages',
            'activeCustomers',
            'topTeknisis',
            'thisMonthRevenue'
        ));
    }

    public function bookingReport(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));
        $status = $request->get('status');
        
        $bookings = Booking::with(['customer.user', 'paket', 'teknisi.user'])
            ->when($startDate, function ($query, $startDate) {
                return $query->whereDate('created_at', '>=', $startDate);
            })
            ->when($endDate, function ($query, $endDate) {
                return $query->whereDate('created_at', '<=', $endDate);
            })
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        // Hitung pendapatan dari booking yang sudah lunas
        $totalRevenue = Booking::where('payment_status', 'paid')
            ->whereBetween('paid_at', [$startDate, $endDate])
            ->with('paket')
            ->get()
            ->sum(function ($booking) {
                return $booking->paket ? $booking->paket->harga : 0;
            });
        
        $summary = [
            'total' => $bookings->total(),
            'completed' => Booking::where('status', 'selesai')->count(),
            'pending' => Booking::where('status', 'pending')->count(),
            'revenue' => $totalRevenue,
        ];
        
        return view('admin.report.booking', compact('bookings', 'startDate', 'endDate', 'status', 'summary'));
    }

    public function paymentReport(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));
        $paymentStatus = $request->get('payment_status');
        
        $payments = Booking::with(['customer.user', 'paket'])
            ->whereNotNull('paid_at')
            ->where('payment_status', 'paid')  // Hanya yang sudah lunas
            ->when($startDate, function ($query, $startDate) {
                return $query->whereDate('paid_at', '>=', $startDate);
            })
            ->when($endDate, function ($query, $endDate) {
                return $query->whereDate('paid_at', '<=', $endDate);
            })
            ->when($paymentStatus, function ($query, $paymentStatus) {
                return $query->where('payment_status', $paymentStatus);
            })
            ->orderBy('paid_at', 'desc')
            ->paginate(20);
        
        $totalPaid = $payments->sum(function ($payment) {
            return $payment->paket ? $payment->paket->harga : 0;
        });
        
        $summary = [
            'total_paid' => $totalPaid,
            'total_transactions' => $payments->count(),
            'avg_transaction' => $payments->count() > 0 ? $totalPaid / $payments->count() : 0,
        ];
        
        return view('admin.report.payment', compact('payments', 'startDate', 'endDate', 'paymentStatus', 'summary'));
    }
}