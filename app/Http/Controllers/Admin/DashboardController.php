<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\Table;
use App\Models\Menu;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. STATISTIK HARI INI
        $today = Carbon::today();
        
        $totalOrders = Order::whereDate('created_at', $today)->count();
        
        $revenueToday = Order::whereDate('created_at', $today)
                            ->where('payment_status', 'paid')
                            ->sum('total_amount');
                            
        $activeTables = Table::where('status', 'occupied')->count();
        $totalTables  = Table::count();
        
        $totalMenus = Menu::where('is_available', true)->count();

        // 2. ORDER TERBARU (5 Transaksi Terakhir)
        $recentOrders = Order::with('table')
                            ->latest()
                            ->take(5)
                            ->get();

        // 3. DATA GRAFIK PENJUALAN MINGGUAN (7 Hari Terakhir)
        $chartLabels = [];
        $chartData   = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date          = Carbon::now()->subDays($i);
            $chartLabels[] = $date->translatedFormat('D');
            $chartData[]   = Order::whereDate('created_at', $date->toDateString())
                                 ->where('payment_status', 'paid')
                                 ->sum('total_amount');
        }

        // 4. TOP 5 MENU FAVORIT BULAN INI (untuk chart)
        $topMenusFav = OrderItem::select('menu_id', DB::raw('SUM(quantity) as total_sold'))
            ->whereHas('order', function($q) {
                $q->where('payment_status', 'paid')
                  ->whereMonth('created_at', Carbon::now()->month)
                  ->whereYear('created_at', Carbon::now()->year);
            })
            ->with('menu')
            ->groupBy('menu_id')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        $favLabels = $topMenusFav->map(fn($item) => $item->menu->name ?? 'N/A')->values()->toArray();
        $favData   = $topMenusFav->map(fn($item) => $item->total_sold)->values()->toArray();

        return view('admin.dashboard.index', compact(
            'totalOrders', 
            'revenueToday', 
            'activeTables', 
            'totalTables',
            'totalMenus', 
            'recentOrders',
            'chartLabels',
            'chartData',
            'favLabels',
            'favData'
        ));
    }
}