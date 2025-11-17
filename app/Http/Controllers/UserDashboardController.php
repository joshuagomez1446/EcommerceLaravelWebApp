<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserDashboardController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();

        // If the user is admin, abort or redirect
        if ($user->is_admin) {
            abort(403, 'Admins cannot access this page.');
            // OR redirect to admin dashboard:
            // return redirect()->route('admin.dashboard');
        }

        // Quick stats
        $totalOrders = $user->orders()->count();
        $pendingOrders = $user->orders()->where('status', 'pending')->count();
        $completedOrders = $user->orders()->where('status', 'completed')->count();
        $totalSpent = $user->orders()->where('status', 'completed')->sum('total_price');

        // Recent orders
        $recentOrders = $user->orders()->latest()->take(5)->get();

        return view('user.dashboard', compact(
            'totalOrders',
            'pendingOrders',
            'completedOrders',
            'totalSpent',
            'recentOrders'
        ));
    }
}
