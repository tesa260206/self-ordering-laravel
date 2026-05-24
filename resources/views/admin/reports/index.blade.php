@extends('layouts.app')

@section('title', 'Laporan Penjualan')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-secondary">Laporan Penjualan</h2>
            <p class="text-sm text-gray-500">Analisa performa penjualan dan menu terlaris restoran.</p>
        </div>
        <div class="flex gap-2 flex-wrap">
            <a href="{{ route('admin.reports.charts', request()->query()) }}" class="bg-secondary text-white px-4 py-2.5 rounded-xl text-sm font-semibold transition shadow-md flex items-center gap-2 hover:bg-gray-800">
                <i data-lucide="bar-chart-3" class="w-4 h-4"></i> Analisa Visual
            </a>
            <a href="{{ route('admin.reports.export-pdf', request()->query()) }}" target="_blank" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2.5 rounded-xl text-sm font-semibold transition shadow-md flex items-center gap-2">
                <i data-lucide="file-text" class="w-4 h-4"></i> Export PDF
            </a>
        </div>
    </div>

    {{-- Filter Preset + Custom --}}
    <div class="bg-surface p-4 rounded-2xl border border-gray-100 shadow-sm">
        {{-- Preset Buttons --}}
        <div class="flex flex-wrap gap-2 mb-4">
            @foreach(['today' => 'Hari Ini', 'week' => 'Minggu Ini', 'month' => 'Bulan Ini', 'year' => 'Tahun Ini', 'custom' => 'Custom'] as $val => $label)
            <a href="{{ route('admin.reports.index', array_merge(request()->except(['period','start_date','end_date']), ['period' => $val])) }}"
               class="px-4 py-1.5 rounded-full text-sm font-semibold border transition
               {{ $period == $val ? 'bg-primary text-white border-primary shadow-md shadow-primary/30' : 'bg-white text-gray-500 border-gray-200 hover:border-primary hover:text-primary' }}">
               {{ $label }}
            </a>
            @endforeach
        </div>

        {{-- Custom Range (ditampilkan hanya jika period=custom) --}}
        <form method="GET" action="{{ route('admin.reports.index') }}" id="customRangeForm" class="{{ $period == 'custom' ? 'flex' : 'hidden' }} flex-wrap items-end gap-4">
            <input type="hidden" name="period" value="custom">
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wider">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}" class="px-4 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary transition outline-none text-sm text-secondary bg-gray-50 focus:bg-white cursor-pointer">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wider">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ $endDate->format('Y-m-d') }}" class="px-4 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary transition outline-none text-sm text-secondary bg-gray-50 focus:bg-white cursor-pointer">
            </div>
            <button type="submit" class="bg-primary hover:bg-[#ca8a04] text-white px-5 py-2 rounded-xl text-sm font-semibold transition shadow-md shadow-primary/30 flex items-center gap-2 h-[38px]">
                <i data-lucide="filter" class="w-4 h-4"></i> Terapkan Filter
            </button>
        </form>

        <p class="text-xs text-gray-400 mt-2">
            <i data-lucide="calendar" class="w-3 h-3 inline-block mr-1"></i>
            Periode aktif: <strong>{{ $startDate->translatedFormat('d F Y') }}</strong> — <strong>{{ $endDate->translatedFormat('d F Y') }}</strong>
        </p>
    </div>

    {{-- Ringkasan Statistik --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="bg-surface p-6 rounded-2xl border border-gray-100 shadow-sm relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-24 h-24 bg-primary/5 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
            <p class="text-sm font-semibold text-gray-500 mb-1">Total Pendapatan</p>
            <h3 class="text-3xl font-bold text-secondary">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
            <div class="mt-4 inline-flex items-center gap-1.5 text-xs font-semibold text-success bg-green-50 px-2 py-1 rounded-lg">
                <i data-lucide="trending-up" class="w-3.5 h-3.5"></i> Lunas
            </div>
        </div>

        <div class="bg-surface p-6 rounded-2xl border border-gray-100 shadow-sm relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-24 h-24 bg-blue-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
            <p class="text-sm font-semibold text-gray-500 mb-1">Total Pesanan</p>
            <h3 class="text-3xl font-bold text-secondary">{{ number_format($totalOrders, 0, ',', '.') }} <span class="text-sm font-normal text-gray-400">Order</span></h3>
            <div class="mt-4 inline-flex items-center gap-1.5 text-xs font-semibold text-blue-600 bg-blue-50 px-2 py-1 rounded-lg">
                <i data-lucide="shopping-bag" class="w-3.5 h-3.5"></i> Selesai
            </div>
        </div>

        <div class="bg-surface p-6 rounded-2xl border border-gray-100 shadow-sm relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-24 h-24 bg-orange-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
            <p class="text-sm font-semibold text-gray-500 mb-1">Rata-rata Order (AOV)</p>
            <h3 class="text-3xl font-bold text-secondary">Rp {{ number_format($averageOrderValue, 0, ',', '.') }}</h3>
            <div class="mt-4 inline-flex items-center gap-1.5 text-xs font-semibold text-warning bg-orange-50 px-2 py-1 rounded-lg">
                <i data-lucide="pie-chart" class="w-3.5 h-3.5"></i> Per Transaksi
            </div>
        </div>
    </div>

    {{-- Grid Tabel --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
        
        {{-- Top 5 Menu Terlaris --}}
        <div class="lg:col-span-1 bg-surface border border-gray-100 rounded-2xl shadow-sm p-5">
            <h3 class="text-lg font-bold text-secondary flex items-center gap-2 mb-4">
                <i data-lucide="award" class="w-5 h-5 text-primary"></i> Top 5 Menu Terlaris
            </h3>
            
            <div class="space-y-4">
                @forelse($topMenus as $index => $item)
                    <div class="flex items-center justify-between p-3 rounded-xl bg-gray-50/50 border border-gray-100 hover:bg-gray-50 transition">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full {{ $index == 0 ? 'bg-primary text-white shadow-md shadow-primary/30' : 'bg-white text-gray-500 border border-gray-200' }} flex items-center justify-center font-bold text-sm shrink-0">
                                {{ $index + 1 }}
                            </div>
                            <div>
                                <p class="text-sm font-bold text-secondary leading-tight">{{ $item->menu->name ?? 'Menu Dihapus' }}</p>
                                <p class="text-[11px] text-gray-500 font-medium">{{ $item->total_sold }} porsi terjual</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] text-gray-400 font-semibold uppercase">Total</p>
                            <p class="text-sm font-bold text-primary leading-tight">Rp {{ number_format($item->total_revenue, 0, ',', '.') }}</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-6 text-gray-500 text-sm">Belum ada data penjualan.</div>
                @endforelse
            </div>
        </div>

        {{-- Rincian Transaksi --}}
        <div class="lg:col-span-2 bg-surface border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
            <div class="p-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                <h3 class="text-lg font-bold text-secondary">Rincian Transaksi</h3>
                <span class="text-xs text-gray-400 font-medium">{{ $orders->count() }} transaksi</span>
            </div>
            
            <div class="overflow-x-auto max-h-[500px] custom-scrollbar">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead class="bg-white text-gray-500 sticky top-0 border-b border-gray-100 z-10 shadow-sm">
                        <tr>
                            <th class="px-5 py-3 font-semibold">Waktu</th>
                            <th class="px-5 py-3 font-semibold">No. Order</th>
                            <th class="px-5 py-3 font-semibold text-center">Meja</th>
                            <th class="px-5 py-3 font-semibold">Metode</th>
                            <th class="px-5 py-3 font-semibold">Kasir</th>
                            <th class="px-5 py-3 font-semibold text-right">Nominal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($orders as $order)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-5 py-3 text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-5 py-3 font-bold text-secondary">{{ $order->order_number }}</td>
                            <td class="px-5 py-3 text-center">
                                <span class="bg-gray-100 px-2 py-1 rounded-md font-bold text-xs">{{ $order->table->table_number }}</span>
                            </td>
                            <td class="px-5 py-3 uppercase text-[11px] font-bold text-gray-500">{{ $order->payment->payment_method ?? '-' }}</td>
                            <td class="px-5 py-3 text-gray-500 text-xs">{{ $order->payment->user->name ?? 'System' }}</td>
                            <td class="px-5 py-3 font-bold text-secondary text-right">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-gray-500">Tidak ada transaksi pada periode ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Tampilkan custom range form jika period=custom di klik dari tombol
    document.querySelectorAll('a[href*="period=custom"]').forEach(btn => {
        btn.addEventListener('click', function(e) {
            const form = document.getElementById('customRangeForm');
            if (form.classList.contains('hidden')) {
                e.preventDefault();
                form.classList.remove('hidden');
                form.classList.add('flex');
            }
        });
    });
</script>
@endpush
@endsection