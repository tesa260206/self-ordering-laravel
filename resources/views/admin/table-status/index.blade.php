@extends('layouts.app')

@section('title', 'Status Meja')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-secondary">Monitoring Status Meja</h2>
            <p class="text-sm text-gray-500">Pantau aktivitas meja dan pesanan pelanggan secara realtime.</p>
        </div>
        
        <div class="flex bg-surface p-1.5 rounded-xl border border-gray-100 shadow-sm gap-2 text-sm font-medium">
            <div class="px-4 py-2 rounded-lg bg-gray-50 text-gray-600 flex items-center gap-2">
                <i data-lucide="grid-2x2" class="w-4 h-4"></i> Total: {{ $totalTables }}
            </div>
            <div class="px-4 py-2 rounded-lg bg-green-50 text-success flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-success"></span> Kosong: {{ $availableTables }}
            </div>
            <div class="px-4 py-2 rounded-lg bg-orange-50 text-warning flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-warning animate-pulse"></span> Terisi: {{ $occupiedTables }}
            </div>
        </div>
    </div>

    <div id="monitoring-container">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
            @forelse($tables as $table)
                @if($table->status == 'available')
                    <div class="bg-surface rounded-2xl border border-gray-100 p-5 shadow-sm flex flex-col items-center justify-center text-center h-48 transition hover:border-green-300">
                        <div class="w-16 h-16 rounded-2xl bg-green-50 text-success flex items-center justify-center mb-3">
                            <span class="text-2xl font-bold">{{ $table->table_number }}</span>
                        </div>
                        <span class="px-3 py-1 text-[10px] uppercase tracking-wider font-bold rounded-full bg-green-50 text-success border border-green-100 mb-2">
                            Kosong (Available)
                        </span>
                        <p class="text-xs text-gray-400">Siap menerima pelanggan</p>
                    </div>
                @else
                    <div class="bg-gradient-to-br from-surface to-orange-50/30 rounded-2xl border border-orange-200 p-5 shadow-md flex flex-col justify-between h-48 relative overflow-hidden transition hover:shadow-lg">
                        
                        <i data-lucide="utensils" class="absolute -bottom-4 -right-4 w-24 h-24 text-orange-500/5 rotate-12"></i>

                        <div class="flex justify-between items-start z-10">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-xl bg-orange-100 text-warning flex items-center justify-center shadow-inner border border-orange-200">
                                    <span class="text-lg font-bold">{{ $table->table_number }}</span>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Meja Terisi</p>
                                    <p class="text-sm font-bold text-secondary">{{ $table->activeOrder ? $table->activeOrder->customer_name ?? 'Tamu Umum' : 'Menunggu Order' }}</p>
                                </div>
                            </div>
                            <span class="relative flex h-3 w-3">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-warning opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-warning"></span>
                            </span>
                        </div>

                        <div class="z-10 bg-white/60 backdrop-blur-sm rounded-xl p-3 border border-white/50">
                            @if($table->activeOrder)
                                <div class="flex justify-between items-center mb-1">
                                    <p class="text-xs text-gray-500">No. Pesanan</p>
                                    <p class="text-xs font-bold text-secondary">{{ $table->activeOrder->order_number }}</p>
                                </div>
                                <div class="flex justify-between items-center mb-2">
                                    <p class="text-xs text-gray-500">Status Masak</p>
                                    @php
                                        $statusColors = [
                                            'pending' => 'text-orange-500',
                                            'cooking' => 'text-blue-500',
                                            'ready' => 'text-green-500'
                                        ];
                                        $statusText = [
                                            'pending' => 'Menunggu',
                                            'cooking' => 'Dimasak',
                                            'ready' => 'Siap Saji'
                                        ];
                                        $currentStatus = $table->activeOrder->status;
                                    @endphp
                                    <p class="text-xs font-bold {{ $statusColors[$currentStatus] ?? 'text-gray-500' }}">
                                        {{ $statusText[$currentStatus] ?? $currentStatus }}
                                    </p>
                                </div>
                                <div class="pt-2 border-t border-gray-200/60 flex justify-between items-center">
                                    <p class="text-[10px] text-gray-400"><i data-lucide="clock" class="w-3 h-3 inline"></i> {{ $table->activeOrder->created_at->diffForHumans() }}</p>
                                    <p class="text-sm font-bold text-primary">Rp {{ number_format($table->activeOrder->total_amount, 0, ',', '.') }}</p>
                                </div>
                            @else
                                <div class="h-full flex flex-col items-center justify-center py-2">
                                    <i data-lucide="loader-2" class="w-5 h-5 text-warning animate-spin mb-1"></i>
                                    <p class="text-xs text-gray-500 font-medium">Pelanggan sedang memilih menu...</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            @empty
                <div class="col-span-full py-16 flex flex-col items-center justify-center bg-surface rounded-2xl border border-gray-100 border-dashed text-center">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4 text-gray-300">
                        <i data-lucide="monitor-off" class="w-10 h-10"></i>
                    </div>
                    <p class="text-secondary font-semibold text-lg">Belum ada data meja</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Fitur Auto-Refresh Dashboard Status Meja (berjalan transparan setiap 15 detik)
    $(document).ready(function() {
        setInterval(function() {
            // Hindari memunculkan efek loading global untuk silent refresh
            let originalGlobalAjaxLoader = $.ajaxSettings.global;
            $.ajaxSetup({ global: false }); 

            $('#monitoring-container').load(window.location.href + ' #monitoring-container > *', function() {
                lucide.createIcons();
            });

            // Kembalikan setting global setelah selesai
            $.ajaxSetup({ global: originalGlobalAjaxLoader });
        }, 15000); // 15 Detik
    });
</script>
@endpush