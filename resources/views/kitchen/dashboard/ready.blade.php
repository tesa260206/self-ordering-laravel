@extends('layouts.kitchen')
@section('title', 'Dapur - Siap Diantar')

@section('content')
<div class="space-y-6 flex flex-col h-full" x-data="{ search: '' }">

    <div class="flex justify-between items-center bg-surface p-5 rounded-2xl border border-gray-100 shadow-sm border-t-4 border-t-success">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-green-50 text-success rounded-xl flex items-center justify-center border border-green-100">
                <i data-lucide="bell-ring" class="w-6 h-6 animate-[wiggle_1s_ease-in-out_infinite]"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-secondary">Siap Diantar</h2>
                <p class="text-xs text-gray-500">Daftar pesanan yang siap diambil oleh pelayan ke meja.</p>
            </div>
        </div>
        
        <div class="relative w-64">
            <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"></i>
            <input type="text" x-model="search" placeholder="Cari meja..." class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-10 pr-4 py-2 text-sm text-secondary focus:border-success focus:ring-2 focus:ring-success/20 outline-none transition">
        </div>
    </div>

    <div class="flex-1 bg-surface rounded-2xl border border-gray-100 overflow-hidden flex flex-col shadow-sm">
        <div class="overflow-x-auto flex-1 custom-scrollbar">
            <table class="w-full text-left border-collapse">
                <thead class="sticky top-0 bg-gray-50 z-10">
                    <tr class="border-b border-gray-100">
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest w-32">Waktu Siap</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest w-48">Tujuan Meja</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Pengecekan Item</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest text-center w-48">Aksi Pelayan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($orders as $order)
                    <tr class="hover:bg-gray-50/50 transition group" 
                        x-show="'{{ $order->table->table_number }} {{ $order->customer_name }}'.toLowerCase().includes(search.toLowerCase())">
                        
                        <td class="px-6 py-5">
                            <span class="text-lg font-black text-secondary">{{ $order->updated_at->format('H:i') }}</span>
                            <p class="text-[10px] text-success font-bold mt-1">{{ $order->updated_at->diffForHumans(null, true) }} lalu</p>
                        </td>

                        <td class="px-6 py-5">
                            <div class="w-16 h-16 rounded-2xl bg-green-50 border border-green-100 flex items-center justify-center text-success font-black text-xl shadow-inner mb-2">
                                {{ $order->table->table_number }}
                            </div>
                            <p class="font-bold text-secondary text-sm truncate">{{ $order->customer_name ?? 'Tamu Umum' }}</p>
                            <p class="text-[10px] text-gray-400">#{{ $order->order_number }}</p>
                        </td>

                        <td class="px-6 py-5">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach($order->items as $item)
                                <div class="flex items-center gap-3 bg-gray-50 p-2 rounded-xl border border-gray-100">
                                    <div class="w-8 h-8 rounded-lg bg-white border border-gray-200 flex items-center justify-center font-black text-secondary shrink-0">
                                        {{ $item->quantity }}
                                    </div>
                                    <p class="text-sm font-medium text-secondary leading-tight truncate">{{ $item->menu->name }}</p>
                                </div>
                                @endforeach
                            </div>
                        </td>

                        <td class="px-6 py-5 text-center">
                            <button onclick="deliverOrder({{ $order->id }})" class="bg-secondary text-white hover:bg-gray-800 font-bold px-6 py-4 rounded-xl transition flex items-center justify-center gap-2 w-full active:scale-95 shadow-md">
                                <i data-lucide="send" class="w-4 h-4"></i>
                                Telah Diantar
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-24 text-center">
                            <div class="flex flex-col items-center text-gray-300">
                                <i data-lucide="check-square" class="w-16 h-16 mb-4"></i>
                                <p class="text-lg font-medium">Belum ada makanan yang siap.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="p-4 bg-gray-50 border-t border-gray-100 flex justify-between items-center text-[10px] text-gray-400 font-bold uppercase tracking-widest">
            <span>{{ $orders->count() }} Antrean Pengantaran</span>
            <span class="flex items-center gap-1"><i data-lucide="refresh-cw" class="w-3 h-3"></i> Refresh Otomatis Aktif</span>
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
        $.ajax({
            url: `/kitchen/orders/${id}/status`,
            type: 'PUT',
            data: { status: 'completed' },
            success: function(res) {
                location.reload(); 
            },
            error: function() {
                Alert.error('Gagal memperbarui status.');
            }
        });
    }

    // Refresh Otomatis setiap 10 detik
    $(document).ready(function() {
        setInterval(() => {
            location.reload();
        }, 10000);
    });
</script>
@endpush