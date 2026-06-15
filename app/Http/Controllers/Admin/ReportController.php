<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Setting;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    /**
     * Ambil tanggal mulai/akhir berdasarkan period atau custom range
     */
    private function getDateRange(Request $request): array
    {
        $period = $request->period ?? 'month';

        switch ($period) {
            case 'today':
                $startDate = Carbon::now()->startOfDay();
                $endDate   = Carbon::now()->endOfDay();
                break;
            case 'week':
                $startDate = Carbon::now()->startOfWeek();
                $endDate   = Carbon::now()->endOfWeek();
                break;
            case 'year':
                $startDate = Carbon::now()->startOfYear();
                $endDate   = Carbon::now()->endOfYear();
                break;
            case 'custom':
                $startDate = $request->start_date ? Carbon::parse($request->start_date)->startOfDay() : Carbon::now()->startOfMonth();
                $endDate   = $request->end_date   ? Carbon::parse($request->end_date)->endOfDay()     : Carbon::now()->endOfDay();
                break;
            case 'month':
            default:
                $startDate = Carbon::now()->startOfMonth();
                $endDate   = Carbon::now()->endOfDay();
                break;
        }

        return [$startDate, $endDate];
    }

    public function index(Request $request)
    {
        [$startDate, $endDate] = $this->getDateRange($request);
        $period = $request->period ?? 'month';

        // 1. Ambil Data Order (Hanya yang lunas)
        $orders = Order::with(['table', 'payment.user'])
            ->where('payment_status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->get();

        // 2. Kalkulasi Ringkasan
        $totalRevenue       = $orders->sum('total_amount');
        $totalOrders        = $orders->count();
        $averageOrderValue  = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        // 3. Analisa Menu Terlaris
        $topMenus = OrderItem::select('menu_id', DB::raw('SUM(quantity) as total_sold'), DB::raw('SUM(subtotal) as total_revenue'))
            ->whereHas('order', function($q) use ($startDate, $endDate) {
                $q->where('payment_status', 'paid')
                  ->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->with('menu')
            ->groupBy('menu_id')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        // 4. Trend penjualan harian dalam rentang
        $days = $startDate->diffInDays($endDate) + 1;
        $maxDays = min($days, 30); // Maksimal 30 hari di chart
        $trendLabels = [];
        $trendData   = [];

        for ($i = $maxDays - 1; $i >= 0; $i--) {
            $date = $endDate->copy()->subDays($i);
            $trendLabels[] = $date->format('d/m');
            $trendData[]   = Order::whereDate('created_at', $date->toDateString())
                                  ->where('payment_status', 'paid')
                                  ->sum('total_amount');
        }

        return view('admin.reports.index', compact(
            'orders', 'totalRevenue', 'totalOrders', 'averageOrderValue',
            'topMenus', 'trendLabels', 'trendData', 'startDate', 'endDate', 'period'
        ));
    }

    public function exportPdf(Request $request)
    {
        [$startDate, $endDate] = $this->getDateRange($request);

        $orders = Order::with(['table', 'payment.user'])
            ->where('payment_status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->get();

        $totalRevenue      = $orders->sum('total_amount');
        $totalOrders       = $orders->count();
        $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        $topMenus = OrderItem::select('menu_id', DB::raw('SUM(quantity) as total_sold'), DB::raw('SUM(subtotal) as total_revenue'))
            ->whereHas('order', function($q) use ($startDate, $endDate) {
                $q->where('payment_status', 'paid')
                  ->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->with('menu')
            ->groupBy('menu_id')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        $setting = Setting::first();

        $pdf = Pdf::loadView('admin.reports.pdf', compact(
            'orders', 'totalRevenue', 'totalOrders', 'averageOrderValue',
            'topMenus', 'startDate', 'endDate', 'setting'
        ))->setPaper('a4', 'portrait');

        $filename = 'laporan-penjualan-' . $startDate->format('Ymd') . '-' . $endDate->format('Ymd') . '.pdf';
        return $pdf->download($filename);
    }
}