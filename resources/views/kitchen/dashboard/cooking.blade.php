@extends('layouts.kitchen')
@section('title', 'KDS - Sedang Dimasak')

@section('content')
<div class="space-y-6 flex flex-col h-full" x-data="{ search: '' }">

    <div class="flex justify-between items-center bg-kds_surface p-5 rounded-2xl border border-kds_border">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-kds_primary/20 text-kds_primary rounded-xl flex items-center justify-center">
                <i data-lucide="flame" class="w-6 h-6 animate-pulse"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-kds_text">Antrean Masakan</h2>
                <p class="text-xs text-kds_text_muted">Daftar pesanan yang sedang diproses di dapur.</p>
            </div>
        </div>
        
        <div class="relative w-64">
            <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-kds_text_muted"></i>
            <input type="text" x-model="search" placeholder="Cari meja atau menu..." class="w-full bg-kds_bg border border-kds_border rounded-xl pl-10 pr-4 py-2 text-sm text-kds_text focus:border-kds_primary outline-none transition">
        </div>
    </div>

    <div class="flex-1 bg-kds_surface rounded-2xl border border-kds_border overflow-hidden flex flex-col shadow-2xl">
        <div class="overflow-x-auto flex-1 custom-scrollbar">
            <table class="w-full text-left border-collapse">
                <thead class="sticky top-0 bg-[#1A1A1A] z-10">
                    <tr class="border-b border-kds_border">
                        <th class="px-6 py-4 text-xs font-bold text-kds_text_muted uppercase tracking-widest">Durasi</th>
                        <th class="px-6 py-4 text-xs font-bold text-kds_text_muted uppercase tracking-widest">Meja</th>
                        <th class="px-6 py-4 text-xs font-bold text-kds_text_muted uppercase tracking-widest">Daftar Menu & Catatan</th>
                        <th class="px-6 py-4 text-xs font-bold text-kds_text_muted uppercase tracking-widest text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-kds_border">
                    @forelse($orders as $order)
                    <tr class="hover:bg-white/[0.02] transition group" 
                        x-show="'{{ $order->table->table_number }} {{ $order->customer_name }}'.toLowerCase().includes(search.toLowerCase())">
                        
                        <td class="px-6 py-6">
                            <div class="flex flex-col">
                                <span class="text-lg font-black text-kds_primary">{{ $order->updated_at->format('H:i') }}</span>
                                <span class="text-[10px] text-kds_text_muted font-medium italic">Mulai masak {{ $order->updated_at->diffForHumans(null, true) }} lalu</span>
                            </div>
                        </td>

                        <td class="px-6 py-6">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-kds_bg border border-kds_border flex items-center justify-center text-kds_text font-bold">
                                    {{ $order->table->table_number }}
                                </div>
                                <div>
                                    <p class="font-bold text-kds_text text-sm">{{ $order->customer_name ?? 'Tamu Umum' }}</p>
                                    <p class="text-[10px] text-kds_text_muted">{{ $order->order_number }}</p>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-6">
                            <div class="space-y-3">
                                @foreach($order->items as $item)
                                <div class="flex items-start gap-2">
                                    <span class="bg-kds_border text-kds_text text-[10px] font-bold px-1.5 py-0.5 rounded shrink-0">{{ $item->quantity }}x</span>
                                    <div>
                                        <p class="text-sm font-semibold text-kds_text leading-none">{{ $item->menu->name }}</p>
                                        @if($item->notes)
                                            <p class="text-[11px] text-kds_primary mt-1 flex items-center gap-1 font-medium italic">
                                                <i data-lucide="info" class="w-3 h-3"></i> {{ $item->notes }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </td>

                        <td class="px-6 py-6 text-center">
                            <button onclick="finishCooking({{ $order->id }})" class="bg-kds_success/10 hover:bg-kds_success text-kds_success hover:text-black font-bold px-6 py-3 rounded-xl transition border border-kds_success/20 flex items-center gap-2 mx-auto active:scale-95">
                                <i data-lucide="check-circle" class="w-4 h-4"></i>
                                Selesai Masak
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center opacity-30">
                                <i data-lucide="chef-hat" class="w-16 h-16 mb-4"></i>
                                <p class="text-lg font-medium">Belum ada pesanan yang sedang dimasak.</p>
                                <p class="text-xs">Ambil pesanan dari tab 'Incoming Order'.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="p-4 bg-[#1A1A1A] border-t border-kds_border flex justify-between items-center text-[10px] text-kds_text_muted font-bold uppercase tracking-widest">
            <span>Menampilkan {{ $orders->count() }} Pesanan Aktif</span>
            <span class="flex items-center gap-1"><i data-lucide="clock" class="w-3 h-3"></i> Refresh Otomatis dalam 10s</span>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function finishCooking(id) {
        // Kita gunakan AJAX yang sudah kita buat di DashboardController sebelumnya
        $.ajax({
            url: `/kitchen/orders/${id}/status`,
            type: 'PUT',
            data: { status: 'ready' },
            success: function(res) {
                // Refresh halaman atau hapus row secara smooth
                location.reload(); 
            },
            error: function() {
                Alert.error('Gagal memperbarui status.');
            }
        });
    }

    // Auto Refresh Halaman Setiap 15 Detik agar data tetap up-to-date
    $(document).ready(function() {
        setInterval(() => {
            location.reload();
        }, 15000);
    });
</script>
@endpush