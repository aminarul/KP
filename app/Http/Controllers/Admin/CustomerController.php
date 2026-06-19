<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        $customers = User::where('role', 'customer')
            ->with('customer')
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                             ->orWhere('email', 'like', "%{$search}%")
                             ->orWhere('phone', 'like', "%{$search}%");
            })
            ->paginate(10);
        
        return view('admin.customer.index', compact('customers', 'search'));
    }

    public function show($id)
    {
        $customer = User::with('customer')->findOrFail($id);
        
        if ($customer->role !== 'customer') {
            abort(404);
        }
        
        return view('admin.customer.show', compact('customer'));
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->role !== 'customer') {
            return back()->with('error', 'Data bukan customer!');
        }
        
        // Delete customer profile if exists
        if ($user->customer) {
            $user->customer->delete();
        }
        
        $user->delete();

        return redirect()->route('admin.customer.index')
                         ->with('success', 'Customer berhasil dihapus!');
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->role !== 'customer') {
            return back()->with('error', 'Data bukan customer!');
        }
        
        $user->update([
            'is_active' => !$user->is_active
        ]);
        
        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        
        return back()->with('success', "Customer berhasil {$status}!");
    }

    // Dashboard customer
    public function customerDashboard()
    {
        $user = auth()->user();
        $customer = $user->customer;

        $myBookings = $customer
            ? $customer->bookings()->latest()->take(5)->get()
            : collect();

        return view('dashboard.customer', compact('user', 'myBookings'));
    }
}