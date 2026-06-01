@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="space-y-6">

    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-secondary">Ringkasan Hari Ini</h2>
            <p class="text-sm text-gray-500">Pantau performa restoran Anda secara realtime.</p>
        </div>
        <div>
            <button class="bg-primary hover:bg-[#EA580C] text-white px-4 py-2 rounded-lg text-sm font-medium transition shadow-sm flex items-center gap-2">
                <i data-lucide="calendar" class="w-4 h-4"></i>
                {{ now()->translatedFormat('d F Y') }}
            </button>
        </div>
    </div>

    {{-- KPI Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-surface p-6 rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Pendapatan Hari Ini</p>
                    <h3 class="text-2xl font-bold text-secondary">Rp {{ number_format($revenueToday, 0, ',', '.') }}</h3>
                </div>
                <div class="p-3 bg-green-50 rounded-lg text-success">
                    <i data-lucide="wallet" class="w-6 h-6"></i>
                </div>
            </div>
            <div class="mt-4 text-xs text-gray-500 flex items-center gap-1">
                Diperbarui otomatis hari ini
            </div>
        </div>

        <div class="bg-surface p-6 rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Total Order</p>
                    <h3 class="text-2xl font-bold text-secondary">{{ $totalOrders }}</h3>
                </div>
                <div class="p-3 bg-primary/10 rounded-lg text-primary">
                    <i data-lucide="shopping-bag" class="w-6 h-6"></i>
                </div>
            </div>
            <div class="mt-4 text-xs text-gray-500 flex items-center gap-1">
                Order masuk hari ini
            </div>
        </div>

        <div class="bg-surface p-6 rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Meja Aktif</p>
                    <h3 class="text-2xl font-bold text-secondary">{{ $activeTables }} <span class="text-sm font-normal text-gray-400">/ {{ $totalTables }} Meja</span></h3>
                </div>
                <div class="p-3 bg-orange-50 rounded-lg text-warning">
                    <i data-lucide="grid-2x2" class="w-6 h-6"></i>
                </div>
            </div>
            <div class="mt-4 text-xs text-gray-500">Live monitoring meja terisi</div>
        </div>

        <div class="bg-surface p-6 rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Menu Tersedia</p>
                    <h3 class="text-2xl font-bold text-secondary">{{ $totalMenus }}</h3>
                </div>
                <div class="p-3 bg-blue-50 rounded-lg text-blue-500">
                    <i data-lucide="utensils" class="w-6 h-6"></i>
                </div>
            </div>
            <div class="mt-4 text-xs text-gray-500">Siap dipesan oleh pelanggan</div>
        </div>
    </div>

    {{-- Charts Row 1 --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Sales Line Chart --}}
        <div class="lg:col-span-2 bg-surface p-6 rounded-xl border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-secondary">Grafik Penjualan 7 Hari Terakhir</h3>
                <a href="{{ route('admin.reports.charts') }}" class="text-xs text-primary hover:underline font-medium flex items-center gap-1">
                    <i data-lucide="external-link" class="w-3 h-3"></i> Lihat Detail
                </a>
            </div>
            <div class="relative h-72 w-full">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        {{-- Recent Orders --}}
        <div class="bg-surface p-6 rounded-xl border border-gray-100 shadow-sm overflow-hidden flex flex-col">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-secondary">Order Terbaru</h3>
                <a href="{{ route('admin.orders.index') }}" class="text-sm text-primary hover:underline font-medium">Lihat Semua</a>
            </div>
            
            <div class="flex-1 overflow-y-auto custom-scrollbar">
                <div class="space-y-4">
                    @forelse($recentOrders as $order)
                        <div class="flex justify-between items-center p-3 hover:bg-gray-50 rounded-lg border border-gray-50 transition cursor-pointer">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold text-sm">
                                    {{ $order->table->table_number ?? '-' }}
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-secondary">{{ $order->order_number }}</p>
                                    <p class="text-xs text-gray-500">{{ $order->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-secondary">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                                @if($order->status == 'pending')
                                    <span class="inline-block mt-1 px-2 py-0.5 bg-gray-100 text-gray-600 text-[10px] font-semibold rounded-full">Pesanan Baru</span>
                                @elseif($order->status == 'cooking')
                                    <span class="inline-block mt-1 px-2 py-0.5 bg-orange-100 text-warning text-[10px] font-semibold rounded-full">Dimasak</span>
                                @elseif($order->status == 'ready')
                                    <span class="inline-block mt-1 px-2 py-0.5 bg-blue-100 text-blue-600 text-[10px] font-semibold rounded-full">Siap Diantar</span>
                                @elseif($order->status == 'completed')
                                    <span class="inline-block mt-1 px-2 py-0.5 bg-green-100 text-success text-[10px] font-semibold rounded-full">Selesai</span>
                                @else
                                    <span class="inline-block mt-1 px-2 py-0.5 bg-red-100 text-danger text-[10px] font-semibold rounded-full">Batal</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <i data-lucide="inbox" class="w-12 h-12 text-gray-300 mx-auto mb-2"></i>
                            <p class="text-sm text-gray-500">Belum ada order hari ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Row 2: Menu Favorit --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Menu Favorit Bulan Ini (List View) --}}
        <div class="bg-surface p-6 rounded-xl border border-gray-100 shadow-sm overflow-hidden flex flex-col">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-secondary flex items-center gap-2">
                    <i data-lucide="award" class="w-5 h-5 text-primary"></i>
                    Menu Favorit Bulan Ini
                </h3>
                <span class="text-xs bg-primary/10 text-primary font-semibold px-2 py-1 rounded-full">Top 5</span>
            </div>
            
            <div class="flex-1 overflow-y-auto custom-scrollbar">
                <div class="space-y-4">
                    @if(count($favLabels) > 0)
                        @foreach($favLabels as $index => $label)
                            <div class="flex justify-between items-center p-3 hover:bg-gray-50 rounded-lg border border-gray-50 transition cursor-pointer">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold text-sm">
                                        #{{ $index + 1 }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-secondary">{{ $label }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-bold text-secondary">{{ $favData[$index] }} Porsi</p>
                                    @if($index == 0)
                                        <span class="inline-block mt-1 px-2 py-0.5 bg-green-100 text-success text-[10px] font-semibold rounded-full">Paling Laris</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-8">
                            <i data-lucide="award" class="w-12 h-12 text-gray-300 mx-auto mb-2 opacity-50"></i>
                            <p class="text-sm text-gray-500">Belum ada data penjualan bulan ini.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();

        // --- Line Chart Penjualan 7 Hari ---
        const ctx = document.getElementById('salesChart').getContext('2d');
        let gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(234, 179, 8, 0.2)'); 
        gradient.addColorStop(1, 'rgba(234, 179, 8, 0)');

        const chartLabels     = {!! json_encode($chartLabels) !!};
        const chartDataValues = {!! json_encode($chartData) !!};

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartLabels, 
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: chartDataValues, 
                    borderColor: '#F97316',
                    backgroundColor: gradient,
                    borderWidth: 2,
                    pointBackgroundColor: '#FFFFFF',
                    pointBorderColor: '#F97316',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#000000', titleFont: { family: 'Poppins', size: 13 },
                        bodyFont: { family: 'Poppins', size: 12 }, padding: 10, cornerRadius: 8, displayColors: false,
                        callbacks: { label: ctx => 'Rp ' + (ctx.raw||0).toLocaleString('id-ID') }
                    }
                },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#F3F4F6', drawBorder: false },
                         ticks: { font: { family: 'Poppins', size: 11 }, color: '#6B7280',
                                  callback: v => v >= 1000000 ? (v/1000000)+'M' : v >= 1000 ? (v/1000)+'K' : v } },
                    x: { grid: { display: false, drawBorder: false },
                         ticks: { font: { family: 'Poppins', size: 11 }, color: '#6B7280' } }
                }
            }
        });

    });
</script>
@endpush