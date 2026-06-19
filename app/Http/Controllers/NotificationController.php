<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->notifications()->paginate(15);
        $unreadCount = Auth::user()->unreadNotifications->count();
        
        // Layout akan ditentukan oleh view yang menggunakan @extends
        // Tidak perlu passing layout ke view
        
        return view('notifications.index', compact('notifications', 'unreadCount'));
    }

    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $url = $notification->data['url'] ?? route('dashboard');
        $notification->markAsRead();
        
        return redirect($url);
    }

    public function markAllAsRead(Request $request)
    {
        Auth::user()->unreadNotifications->markAsRead();
        
        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Semua notifikasi telah dibaca']);
        }
        
        // Cek role user untuk redirect yang tepat
        $redirectRoute = match(Auth::user()->role) {
            'admin' => 'admin.dashboard',
            'teknisi' => 'teknisi.dashboard',
            default => 'customer.dashboard',
        };
        
        return redirect()->route($redirectRoute)->with('success', 'Semua notifikasi telah ditandai dibaca.');
    }

    public function getUnreadCount()
    {
        return response()->json([
            'count' => Auth::user()->unreadNotifications->count()
        ]);
    }
}