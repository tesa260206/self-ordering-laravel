@extends('layouts.kitchen')
@section('title', 'KDS - Siap Diantar')

@section('content')
<div class="space-y-6 flex flex-col h-full" x-data="{ search: '' }">

    <div class="flex justify-between items-center bg-kds_surface p-5 rounded-2xl border border-kds_border border-t-4 border-t-kds_success shadow-lg">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-kds_success/20 text-kds_success rounded-xl flex items-center justify-center">
                <i data-lucide="bell-ring" class="w-6 h-6 animate-[wiggle_1s_ease-in-out_infinite]"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-kds_text">Siap Diantar (Pick-up)</h2>
                <p class="text-xs text-kds_text_muted">Daftar pesanan yang siap diambil oleh pelayan ke meja.</p>
            </div>
        </div>
        
        <div class="relative w-64">
            <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-kds_text_muted"></i>
            <input type="text" x-model="search" placeholder="Cari meja..." class="w-full bg-kds_bg border border-kds_border rounded-xl pl-10 pr-4 py-2 text-sm text-kds_text focus:border-kds_success outline-none transition">
        </div>
    </div>

    <div class="flex-1 bg-kds_surface rounded-2xl border border-kds_border overflow-hidden flex flex-col shadow-2xl">
        <div class="overflow-x-auto flex-1 custom-scrollbar">
            <table class="w-full text-left border-collapse">
                <thead class="sticky top-0 bg-[#1A1A1A] z-10">
                    <tr class="border-b border-kds_border">
                        <th class="px-6 py-4 text-xs font-bold text-kds_text_muted uppercase tracking-widest w-32">Waktu Siap</th>
                        <th class="px-6 py-4 text-xs font-bold text-kds_text_muted uppercase tracking-widest w-48">Tujuan Meja</th>
                        <th class="px-6 py-4 text-xs font-bold text-kds_text_muted uppercase tracking-widest">Pengecekan Item</th>
                        <th class="px-6 py-4 text-xs font-bold text-kds_text_muted uppercase tracking-widest text-center w-48">Aksi Pelayan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-kds_border">
                    @forelse($orders as $order)
                    <tr class="hover:bg-white/[0.02] transition group" 
                        x-show="'{{ $order->table->table_number }} {{ $order->customer_name }}'.toLowerCase().includes(search.toLowerCase())">
                        
                        <td class="px-6 py-5">
                            <span class="text-lg font-black text-kds_text">{{ $order->updated_at->format('H:i') }}</span>
                            <p class="text-[10px] text-kds_success font-bold mt-1">{{ $order->updated_at->diffForHumans(null, true) }} lalu</p>
                        </td>

                        <td class="px-6 py-5">
                            <div class="w-16 h-16 rounded-2xl bg-kds_success/10 border border-kds_success/30 flex items-center justify-center text-kds_success font-black text-xl shadow-inner mb-2">
                                {{ $order->table->table_number }}
                            </div>
                            <p class="font-bold text-kds_text text-sm truncate">{{ $order->customer_name ?? 'Tamu Umum' }}</p>
                            <p class="text-[10px] text-kds_text_muted">#{{ $order->order_number }}</p>
                        </td>

                        <td class="px-6 py-5">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach($order->items as $item)
                                <div class="flex items-center gap-3 bg-[#1A1A1A] p-2 rounded-xl border border-kds_border">
                                    <div class="w-8 h-8 rounded-lg bg-kds_bg flex items-center justify-center font-black text-kds_text shrink-0">
                                        {{ $item->quantity }}
                                    </div>
                                    <p class="text-sm font-medium text-kds_text leading-tight truncate">{{ $item->menu->name }}</p>
                                </div>
                                @endforeach
                            </div>
                        </td>

                        <td class="px-6 py-5 text-center">
                            <button onclick="deliverOrder({{ $order->id }})" class="bg-kds_text text-kds_bg hover:bg-gray-300 font-bold px-6 py-4 rounded-xl transition flex items-center justify-center gap-2 w-full active:scale-95 shadow-lg">
                                <i data-lucide="send" class="w-4 h-4"></i>
                                Telah Diantar
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-24 text-center">
                            <div class="flex flex-col items-center opacity-30">
                                <i data-lucide="check-square" class="w-16 h-16 mb-4"></i>
                                <p class="text-lg font-medium">Belum ada makanan yang siap.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="p-4 bg-[#1A1A1A] border-t border-kds_border flex justify-between items-center text-[10px] text-kds_text_muted font-bold uppercase tracking-widest">
            <span>{{ $orders->count() }} Antrean Pengantaran</span>
            <span class="flex items-center gap-1"><i data-lucide="refresh-cw" class="w-3 h-3"></i> Auto Refresh Aktif</span>
        </div>
    </div>
</div>

<style>
    @keyframes wiggle {
        0%, 100% { transform: rotate(-10deg); }
        50% { transform: rotate(10deg); }
    }
</style>
@endsection

@push('scripts')
<script>
    function deliverOrder(id) {
        // Update status menjadi 'completed' (atau status custom lain jika ada)
        $.ajax({
            url: `/kitchen/orders/${id}/status`,
            type: 'PUT',
            data: { status: 'completed' }, // Status akhir
            success: function(res) {
                location.reload(); 
            },
            error: function() {
                Alert.error('Gagal memperbarui status.');
            }
        });
    }

    // Auto Refresh setiap 10 detik
    $(document).ready(function() {
        setInterval(() => {
            location.reload();
        }, 10000);
    });
</script>
@endpush