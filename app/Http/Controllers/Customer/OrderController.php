<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Table;
use App\Models\Setting;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;

class OrderController extends Controller
{
    public function welcome(Request $request)
    {
        $tableNumber = $request->query('table');
        $table = Table::where('table_number', $tableNumber)->first();

        // JIKA MEJA TIDAK DITEMUKAN
        if (!$table) {
            $setting = Setting::first();
            return view('customer.invalid-table', compact('setting'));
        }

        // Ambil profil restoran jika meja valid
        $setting = Setting::first();

        return view('customer.welcome', compact('table', 'setting'));
    }

    public function menu(Request $request)
    {
        $tableNumber = $request->query('table');
        $table = Table::where('table_number', $tableNumber)->first();

        if (!$table) {
            $setting = Setting::first();
            return view('customer.invalid-table', compact('setting'));
        }

        // Ambil Data Kategori & Menu yang Aktif
        $categories = Category::where('is_active', true)->get();
        $menus = Menu::with('category')->where('is_available', true)->get();
        $setting = Setting::first();

        return view('customer.menu', compact('table', 'categories', 'menus', 'setting'));
    }

    public function checkout(Request $request)
    {
        $tableNumber = $request->query('table');
        $table = Table::where('table_number', $tableNumber)->firstOrFail();
        $setting = Setting::first();

        return view('customer.checkout', compact('table', 'setting'));
    }

    public function storeOrder(Request $request)
    {
        $request->validate([
            'table_id' => 'required|exists:tables,id',
            'customer_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'items' => 'required|array',
            'items.*.id' => 'required|exists:menus,id',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric',
        ]);

        DB::beginTransaction();
        try {
            $setting = Setting::first();
            
            // 1. CEK TRANSAKSI AKTIF (MERGE ORDER LOGIC)
            $activeOrder = Order::where('table_id', $request->table_id)
                                ->where('payment_status', 'unpaid')
                                ->first();

            if ($activeOrder) {
                // JIKA ADA: Gunakan Order ID yang sudah ada
                $order = $activeOrder;
                
                // Kembalikan status ke 'pending' agar muncul lagi di layar KDS Dapur
                $order->status = 'pending';
                $order->save();
            } else {
                // JIKA TIDAK ADA: Buat Order Baru
                $todayOrders = Order::whereDate('created_at', today())->count() + 1;
                $orderNumber = 'ORD-' . date('dmy') . '-' . str_pad($todayOrders, 3, '0', STR_PAD_LEFT);

                $order = Order::create([
                    'order_number' => $orderNumber,
                    'table_id' => $request->table_id,
                    'customer_name' => $request->customer_name,
                    'phone' => $request->phone,
                    'total_amount' => 0, // Set 0 dulu, akan dikalkulasi ulang di bawah
                    'status' => 'pending',
                    'payment_status' => 'unpaid'
                ]);

                // Ubah status meja menjadi terisi
                Table::where('id', $request->table_id)->update(['status' => 'occupied']);
            }

            // 2. MASUKKAN ITEM BARU KE DATABASE
            foreach($request->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id' => $item['id'],
                    'quantity' => $item['qty'],
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['qty'],
                    'notes' => $item['notes'] ?? null
                ]);
            }

            // 3. HITUNG ULANG TOTAL TAGIHAN (Seluruh item lama + item baru)
            $subtotal = OrderItem::where('order_id', $order->id)->sum('subtotal');
            $tax = $subtotal * ($setting->tax / 100);
            $total = $subtotal + $tax;

            // 4. UPDATE TOTAL AMOUNT
            $order->update(['total_amount' => $total]);

            // Ambil nomor meja untuk redirect
            $table = Table::find($request->table_id);

            DB::commit();

            return response()->json([
                'success' => true,
                'redirect_url' => route('customer.success', ['orderNumber' => $order->order_number, 'table' => $table->table_number])
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal memproses pesanan.'], 500);
        }
    }

    public function success(Request $request, $orderNumber)
    {
        $tableNumber = $request->query('table');
        $order = Order::with(['items.menu'])->where('order_number', $orderNumber)->firstOrFail();
        $setting = Setting::first();

        return view('customer.success', compact('order', 'tableNumber', 'setting'));
    }

    public function status(Request $request, $orderNumber)
    {
        $tableNumber = $request->query('table');
        $order = Order::with(['items.menu'])->where('order_number', $orderNumber)->firstOrFail();
        $setting = Setting::first();

        return view('customer.status', compact('order', 'tableNumber', 'setting'));
    }

    public function checkStatus($orderNumber)
    {
        // Method ringan untuk di-hit oleh Alpine.js setiap beberapa detik
        $order = Order::where('order_number', $orderNumber)->firstOrFail();
        
        return response()->json([
            'status' => $order->status,
            'time' => $order->updated_at->format('H:i')
        ]);
    }
    
    public function ready()
    {
        $orders = Order::with(['table', 'items.menu'])
            ->where('status', 'ready')
            ->oldest()
            ->get();

        return view('kitchen.dashboard.ready', compact('orders'));
    }

    /**
     * Cek apakah meja sudah punya order aktif (unpaid).
     * Jika ada, kembalikan data customer agar tidak perlu input ulang.
     */
    public function checkActiveOrder(Request $request)
    {
        $tableId = $request->query('table_id');

        if (!$tableId) {
            return response()->json(['has_active_order' => false]);
        }

        $activeOrder = Order::where('table_id', $tableId)
                            ->where('payment_status', 'unpaid')
                            ->latest()
                            ->first();

        if ($activeOrder) {
            return response()->json([
                'has_active_order' => true,
                'order_number'     => $activeOrder->order_number,
                'customer_name'    => $activeOrder->customer_name,
                'phone'            => $activeOrder->phone ?? '',
                'items_count'      => $activeOrder->items()->count(),
            ]);
        }

        return response()->json(['has_active_order' => false]);
    }
}