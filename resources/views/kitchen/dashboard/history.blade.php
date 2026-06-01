@extends('layouts.kitchen')
@section('title', 'KDS - Riwayat Masakan')

@section('content')
<div class="space-y-6 flex flex-col h-full">

    <div class="bg-kds_surface p-5 rounded-2xl border border-kds_border shadow-lg flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-gray-800 text-gray-400 rounded-xl flex items-center justify-center border border-kds_border">
                <i data-lucide="history" class="w-6 h-6"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-kds_text">Riwayat Masakan</h2>
                <p class="text-xs text-kds_text_muted">Rekapitulasi pesanan yang telah diselesaikan.</p>
            </div>
        </div>
        
        <form method="GET" action="{{ route('kitchen.history') }}" class="flex flex-wrap items-center gap-3 w-full md:w-auto">
            <div class="relative">
                <input type="date" name="date" value="{{ request('date', now()->format('Y-m-d')) }}" class="bg-kds_bg border border-kds_border rounded-xl px-4 py-2 text-sm text-kds_text focus:border-kds_primary outline-none transition cursor-pointer">
            </div>
            <div class="relative w-full md:w-56">
                <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-kds_text_muted"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari pesanan..." class="w-full bg-kds_bg border border-kds_border rounded-xl pl-10 pr-4 py-2 text-sm text-kds_text focus:border-kds_primary outline-none transition">
            </div>
            <button type="submit" class="bg-kds_primary hover:bg-[#EA580C] text-black font-bold px-4 py-2 rounded-xl transition flex items-center gap-2">
                <i data-lucide="filter" class="w-4 h-4"></i> Filter
            </button>
            <a href="{{ route('kitchen.history') }}" class="text-kds_text_muted hover:text-white px-3 py-2 text-sm font-medium transition">
                Reset
            </a>
        </form>
    </div>

    <div class="flex-1 bg-kds_surface rounded-2xl border border-kds_border overflow-hidden flex flex-col shadow-2xl">
        <div class="overflow-x-auto flex-1 custom-scrollbar">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead class="sticky top-0 bg-[#1A1A1A] z-10">
                    <tr class="border-b border-kds_border">
                        <th class="px-6 py-4 text-xs font-bold text-kds_text_muted uppercase tracking-widest w-48">Waktu Selesai</th>
                        <th class="px-6 py-4 text-xs font-bold text-kds_text_muted uppercase tracking-widest">Detail Pesanan</th>
                        <th class="px-6 py-4 text-xs font-bold text-kds_text_muted uppercase tracking-widest text-center">Item</th>
                        <th class="px-6 py-4 text-xs font-bold text-kds_text_muted uppercase tracking-widest text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-kds_border">
                    @forelse($orders as $order)
                    <tr class="hover:bg-white/[0.02] transition">
                        
                        <td class="px-6 py-4">
                            <span class="text-sm font-bold text-kds_text">{{ $order->updated_at->format('d M Y') }}</span>
                            <p class="text-[11px] text-kds_text_muted mt-0.5">{{ $order->updated_at->format('H:i') }} WIB</p>
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-kds_bg border border-kds_border flex items-center justify-center text-kds_text font-bold shrink-0">
                                    {{ $order->table->table_number }}
                                </div>
                                <div>
                                    <p class="font-bold text-kds_text text-sm">{{ $order->order_number }}</p>
                                    <p class="text-[11px] text-kds_text_muted">{{ $order->customer_name ?? 'Tamu Umum' }}</p>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4 text-center">
                            <span class="bg-gray-800 text-kds_text px-3 py-1 rounded-md text-xs font-bold border border-kds_border">
                                {{ $order->items->sum('quantity') }} Porsi
                            </span>
                        </td>

                        <td class="px-6 py-4 text-center">
                            @if($order->status === 'completed')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-kds_success/10 text-kds_success border border-kds_success/20 rounded-full text-[10px] font-bold uppercase tracking-wider">
                                    <i data-lucide="check-check" class="w-3 h-3"></i> Selesai
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-kds_danger/10 text-kds_danger border border-kds_danger/20 rounded-full text-[10px] font-bold uppercase tracking-wider">
                                    <i data-lucide="x-circle" class="w-3 h-3"></i> Dibatalkan
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center opacity-30">
                                <i data-lucide="folder-search" class="w-16 h-16 mb-4"></i>
                                <p class="text-lg font-medium">Tidak ada riwayat masakan.</p>
                                <p class="text-xs">Coba ubah tanggal atau kata kunci pencarian.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="p-4 bg-[#1A1A1A] border-t border-kds_border">
            {{ $orders->links() }}
        </div>
    </div>
</div>

<style>
    nav[aria-label="Pagination"] p { color: #9CA3AF !important; font-size: 12px; }
    nav[aria-label="Pagination"] span[aria-current="page"] span { 
        background-color: #F97316 !important; 
        color: #000 !important; 
        border-color: #F97316 !important; 
    }
    nav[aria-label="Pagination"] a, nav[aria-label="Pagination"] span.relative { 
        background-color: #1E1E1E !important; 
        border-color: #2C2C2C !important; 
        color: #E5E7EB !important; 
    }
    nav[aria-label="Pagination"] a:hover { 
        background-color: #2C2C2C !important; 
    }
    nav[aria-label="Pagination"] svg { stroke: currentColor; }
</style>
@endsection