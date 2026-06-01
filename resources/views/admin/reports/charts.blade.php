@extends('layouts.app')

@section('title', 'Analisa Visual Penjualan')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-secondary">Analisa Visual</h2>
            <p class="text-sm text-gray-500">Visualisasi data penjualan, menu favorit & tren transaksi.</p>
        </div>
        <div class="flex gap-2 flex-wrap">
            <a href="{{ route('admin.reports.index', request()->query()) }}" class="bg-white border border-gray-200 text-gray-600 hover:text-primary hover:border-primary px-4 py-2.5 rounded-xl text-sm font-semibold transition flex items-center gap-2">
                <i data-lucide="table-2" class="w-4 h-4"></i> Laporan Tabel
            </a>
            <a href="{{ route('admin.reports.export-pdf', request()->query()) }}" target="_blank" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2.5 rounded-xl text-sm font-semibold transition shadow-md flex items-center gap-2">
                <i data-lucide="file-text" class="w-4 h-4"></i> Export PDF
            </a>
        </div>
    </div>

    {{-- Filter Preset --}}
    <div class="bg-surface p-4 rounded-2xl border border-gray-100 shadow-sm">
        <form method="GET" action="{{ route('admin.reports.charts') }}" id="customRangeForm" class="flex flex-wrap items-end gap-4">
            <input type="hidden" name="period" value="custom">
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wider">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}" class="px-4 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary transition outline-none text-sm">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wider">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ $endDate->format('Y-m-d') }}" class="px-4 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary transition outline-none text-sm">
            </div>
            <button type="submit" class="bg-primary hover:bg-[#EA580C] text-white px-5 py-2 rounded-xl text-sm font-semibold transition shadow-md shadow-primary/30 flex items-center gap-2 h-[38px]">
                <i data-lucide="filter" class="w-4 h-4"></i> Terapkan
            </button>
        </form>
        <p class="text-xs text-gray-400 mt-2">
            <i data-lucide="calendar" class="w-3 h-3 inline-block mr-1"></i>
            Periode: <strong>{{ $startDate->translatedFormat('d F Y') }}</strong> — <strong>{{ $endDate->translatedFormat('d F Y') }}</strong>
        </p>
    </div>

    {{-- Ringkasan KPI Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="bg-surface p-6 rounded-2xl border border-gray-100 shadow-sm relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-24 h-24 bg-primary/5 rounded-bl-full -mr-4 -mt-4"></div>
            <p class="text-sm font-semibold text-gray-500 mb-1">Total Pendapatan</p>
            <h3 class="text-3xl font-bold text-secondary">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
            <div class="mt-4 inline-flex items-center gap-1.5 text-xs font-semibold text-success bg-green-50 px-2 py-1 rounded-lg">
                <i data-lucide="trending-up" class="w-3.5 h-3.5"></i> Periode Ini
            </div>
        </div>
        <div class="bg-surface p-6 rounded-2xl border border-gray-100 shadow-sm relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-24 h-24 bg-blue-50 rounded-bl-full -mr-4 -mt-4"></div>
            <p class="text-sm font-semibold text-gray-500 mb-1">Total Pesanan</p>
            <h3 class="text-3xl font-bold text-secondary">{{ number_format($totalOrders, 0, ',', '.') }} <span class="text-sm font-normal text-gray-400">Order</span></h3>
            <div class="mt-4 inline-flex items-center gap-1.5 text-xs font-semibold text-blue-600 bg-blue-50 px-2 py-1 rounded-lg">
                <i data-lucide="shopping-bag" class="w-3.5 h-3.5"></i> Selesai
            </div>
        </div>
        <div class="bg-surface p-6 rounded-2xl border border-gray-100 shadow-sm relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-24 h-24 bg-orange-50 rounded-bl-full -mr-4 -mt-4"></div>
            <p class="text-sm font-semibold text-gray-500 mb-1">Rata-rata Order</p>
            <h3 class="text-3xl font-bold text-secondary">Rp {{ number_format($averageOrderValue, 0, ',', '.') }}</h3>
            <div class="mt-4 inline-flex items-center gap-1.5 text-xs font-semibold text-warning bg-orange-50 px-2 py-1 rounded-lg">
                <i data-lucide="pie-chart" class="w-3.5 h-3.5"></i> Per Transaksi
            </div>
        </div>
    </div>

    {{-- Chart Row 1: Tren Penjualan + Menu Terlaris (Bar) --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Tren Penjualan Harian --}}
        <div class="lg:col-span-2 bg-surface p-6 rounded-2xl border border-gray-100 shadow-sm">
            <h3 class="text-lg font-bold text-secondary mb-4 flex items-center gap-2">
                <i data-lucide="trending-up" class="w-5 h-5 text-primary"></i>
                Tren Penjualan Harian
            </h3>
            <div class="relative h-72">
                <canvas id="trendChart"></canvas>
            </div>
        </div>

        {{-- Top Menu Terlaris (Doughnut) --}}
        <div class="bg-surface p-6 rounded-2xl border border-gray-100 shadow-sm">
            <h3 class="text-lg font-bold text-secondary mb-4 flex items-center gap-2">
                <i data-lucide="award" class="w-5 h-5 text-primary"></i>
                Distribusi Menu Terlaris
            </h3>
            <div class="relative h-56">
                <canvas id="doughnutChart"></canvas>
            </div>
            <div class="mt-3 space-y-1" id="doughnutLegend"></div>
        </div>
    </div>

    {{-- Chart Row 2: Horizontal Bar Menu + Revenue --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Bar Chart Qty Terjual --}}
        <div class="bg-surface p-6 rounded-2xl border border-gray-100 shadow-sm">
            <h3 class="text-lg font-bold text-secondary mb-4 flex items-center gap-2">
                <i data-lucide="bar-chart-horizontal" class="w-5 h-5 text-primary"></i>
                Porsi Terjual per Menu
            </h3>
            <div class="relative h-64">
                <canvas id="barQtyChart"></canvas>
            </div>
        </div>

        {{-- Bar Chart Revenue per Menu --}}
        <div class="bg-surface p-6 rounded-2xl border border-gray-100 shadow-sm">
            <h3 class="text-lg font-bold text-secondary mb-4 flex items-center gap-2">
                <i data-lucide="coins" class="w-5 h-5 text-primary"></i>
                Revenue per Menu (Rp)
            </h3>
            <div class="relative h-64">
                <canvas id="barRevenueChart"></canvas>
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

    const menuNames   = {!! json_encode($topMenus->map(fn($m) => $m->menu->name ?? 'N/A')->values()) !!};
    const menuQty     = {!! json_encode($topMenus->map(fn($m) => $m->total_sold)->values()) !!};
    const menuRevenue = {!! json_encode($topMenus->map(fn($m) => $m->total_revenue)->values()) !!};
    const trendLabels = {!! json_encode($trendLabels) !!};
    const trendValues = {!! json_encode($trendData) !!};

    const colors = ['#F97316','#3B82F6','#10B981','#F59E0B','#8B5CF6','#EF4444','#06B6D4','#84CC16','#EC4899','#F97316'];

    // --- Tren Chart (Line) ---
    const trendCtx = document.getElementById('trendChart').getContext('2d');
    let grad = trendCtx.createLinearGradient(0,0,0,300);
    grad.addColorStop(0,'rgba(234,179,8,0.25)');
    grad.addColorStop(1,'rgba(234,179,8,0)');
    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: trendLabels,
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: trendValues,
                borderColor: '#F97316',
                backgroundColor: grad,
                borderWidth: 2.5,
                pointBackgroundColor: '#fff',
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
                    backgroundColor: '#000000', cornerRadius: 8, padding: 10,
                    displayColors: false, bodyFont: { family: 'Poppins' },
                    callbacks: { label: ctx => 'Rp ' + (ctx.raw||0).toLocaleString('id-ID') }
                }
            },
            scales: {
                y: { beginAtZero: true, grid: { color: '#F3F4F6' },
                     ticks: { font: {family:'Poppins',size:11}, color:'#6B7280',
                              callback: v => v >= 1000000 ? (v/1000000)+'M' : v >= 1000 ? (v/1000)+'K' : v } },
                x: { grid: { display: false }, ticks: { font: {family:'Poppins',size:11}, color:'#6B7280' } }
            }
        }
    });

    // --- Doughnut Chart ---
    const dCtx = document.getElementById('doughnutChart').getContext('2d');
    new Chart(dCtx, {
        type: 'doughnut',
        data: {
            labels: menuNames,
            datasets: [{ data: menuQty, backgroundColor: colors.slice(0, menuNames.length), borderWidth: 2, borderColor: '#fff' }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            cutout: '65%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#000000', cornerRadius: 8,
                    callbacks: { label: ctx => ` ${ctx.label}: ${ctx.raw} porsi` }
                }
            }
        }
    });

    // Legend manual
    const legendEl = document.getElementById('doughnutLegend');
    menuNames.forEach((name, i) => {
        legendEl.innerHTML += `<div class="flex items-center gap-2 text-xs">
            <span style="width:10px;height:10px;border-radius:50%;background:${colors[i]};flex-shrink:0;display:inline-block;"></span>
            <span class="text-gray-600 truncate">${name}</span>
            <span class="ml-auto font-bold text-secondary">${menuQty[i]} pcs</span>
        </div>`;
    });

    // --- Bar Qty Chart ---
    new Chart(document.getElementById('barQtyChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: menuNames,
            datasets: [{ label: 'Porsi Terjual', data: menuQty,
                backgroundColor: menuNames.map((_, i) => colors[i] + 'CC'),
                borderColor: menuNames.map((_, i) => colors[i]),
                borderWidth: 1.5, borderRadius: 6 }]
        },
        options: {
            responsive: true, maintainAspectRatio: false, indexAxis: 'y',
            plugins: { legend: { display: false }, tooltip: {
                backgroundColor: '#000000', cornerRadius: 8,
                callbacks: { label: ctx => ` ${ctx.raw} porsi` }
            }},
            scales: {
                x: { beginAtZero: true, grid: { color: '#F3F4F6' },
                     ticks: { font: {family:'Poppins',size:11}, color: '#6B7280' } },
                y: { grid: { display: false }, ticks: { font: {family:'Poppins',size:11}, color: '#374151' } }
            }
        }
    });

    // --- Bar Revenue Chart ---
    new Chart(document.getElementById('barRevenueChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: menuNames,
            datasets: [{ label: 'Revenue (Rp)', data: menuRevenue,
                backgroundColor: '#F97316CC', borderColor: '#F97316',
                borderWidth: 1.5, borderRadius: 6 }]
        },
        options: {
            responsive: true, maintainAspectRatio: false, indexAxis: 'y',
            plugins: { legend: { display: false }, tooltip: {
                backgroundColor: '#000000', cornerRadius: 8,
                callbacks: { label: ctx => ' Rp ' + (ctx.raw||0).toLocaleString('id-ID') }
            }},
            scales: {
                x: { beginAtZero: true, grid: { color: '#F3F4F6' },
                     ticks: { font: {family:'Poppins',size:11}, color:'#6B7280',
                              callback: v => v >= 1000000 ? (v/1000000)+'M' : v >= 1000 ? (v/1000)+'K' : v } },
                y: { grid: { display: false }, ticks: { font: {family:'Poppins',size:11}, color: '#374151' } }
            }
        }
    });
});
</script>
@endpush
