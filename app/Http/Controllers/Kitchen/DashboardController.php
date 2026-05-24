<?php

namespace App\Http\Controllers\Kitchen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Pesanan Baru Masuk (Pending)
        $incomingOrders = Order::with(['table', 'items.menu'])
            ->where('status', 'pending')
            ->oldest()
            ->get();

        // 2. Pesanan Sedang Dimasak (Cooking)
        $cookingOrders = Order::with(['table', 'items'])
            ->where('status', 'cooking')
            ->oldest()
            ->get();

        // 3. Pesanan Siap Diantar (Ready) - Tampilkan yang terbaru saja (opsional)
        $readyOrders = Order::with(['table', 'items'])
            ->where('status', 'ready')
            ->latest()
            ->take(5)
            ->get();

        return view('kitchen.dashboard.index', compact('incomingOrders', 'cookingOrders', 'readyOrders'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:cooking,ready,completed'
        ]);

        $order->update([
            'status' => $request->status
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status pesanan diupdate ke: ' . $request->status
        ]);
    }

    public function cooking()
    {
        // Ambil hanya pesanan yang statusnya 'cooking'
        $orders = Order::with(['table', 'items.menu'])
            ->where('status', 'cooking')
            ->oldest() // Urutkan dari yang paling lama dimasak agar koki tidak lupa
            ->get();

        return view('kitchen.dashboard.cooking', compact('orders'));
    }

    public function ready()
    {
        // Ambil pesanan yang sudah selesai dimasak (status: ready)
        $orders = Order::with(['table', 'items.menu'])
            ->where('status', 'ready')
            ->oldest() // Prioritaskan yang sudah siap paling awal
            ->get();

        return view('kitchen.dashboard.ready', compact('orders'));
    }
    public function history(Request $request)
    {
        // Query dasar: Ambil order yang sudah selesai atau dibatalkan
        $query = Order::with(['table', 'items.menu'])
                      ->whereIn('status', ['completed', 'cancelled']);

        // Filter Tanggal (Jika ada)
        if ($request->filled('date')) {
            $query->whereDate('updated_at', $request->date);
        } else {
            // Default: Tampilkan hari ini agar tidak terlalu berat memuat semua data lama
            $query->whereDate('updated_at', today());
        }

        // Filter Pencarian (No Order, Nama, atau Meja)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhereHas('table', function($t) use ($search) {
                      $t->where('table_number', 'like', "%{$search}%");
                  });
            });
        }

        // Urutkan dari yang paling baru selesai, Pagination 12 data
        $orders = $query->latest('updated_at')->paginate(12)->withQueryString();

        return view('kitchen.dashboard.history', compact('orders'));
    }
}