<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;

class UserOrderController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();

        $totalOrders = $user->orders()->count();
        $pendingOrders = $user->orders()->where('status', 'pending')->count();
        $completedOrders = $user->orders()->where('status', 'completed')->count();
        $totalSpent = $user->orders()->where('status', 'completed')->sum('total_price');

        $recentOrders = $user->orders()->latest()->take(5)->get();

        return view('user.dashboard', compact(
            'totalOrders',
            'pendingOrders',
            'completedOrders',
            'totalSpent',
            'recentOrders'
        ));
    }

    public function ordersIndex()
    {
        $user = auth()->user();

        // Get all orders of the logged-in user, latest first, with pagination
        $orders = $user->orders()->latest()->paginate(10);

        return view('user.orders.index', compact('orders'));
    }

    public function showOrder(Order $order)
    {
        // Ensure the order belongs to the authenticated user
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        // Eager load products and pivot data
        $order->load('products');

        return view('user.orders.show', compact('order'));
    }
}
