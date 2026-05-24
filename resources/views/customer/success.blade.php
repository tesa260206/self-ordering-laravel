@extends('layouts.customer')
@section('title', 'Pesanan Diterima')

@section('content')
<div class="flex flex-col min-h-screen bg-white relative">
    
    <div class="sticky top-0 z-40 bg-white px-5 py-4 flex items-center justify-between border-b border-gray-100 shadow-sm">
        <div class="w-10"></div>
        <h1 class="text-lg font-bold text-secondary">Pesanan Sukses</h1>
        <div class="w-10"></div>
    </div>

    <div class="flex-1 flex flex-col items-center justify-center p-8 text-center mt-10">
        <div class="w-28 h-28 bg-green-50 rounded-full flex items-center justify-center mb-6 shadow-inner border-4 border-white">
            <i data-lucide="check-circle-2" class="w-14 h-14 text-success animate-[bounce_1s_ease-out]"></i>
        </div>
        
        <h2 class="text-2xl font-extrabold text-secondary mb-2">Terima Kasih!</h2>
        <p class="text-sm text-gray-500 mb-8 px-4 leading-relaxed">Pesanan Anda telah masuk ke dapur kami dan sedang diproses. Silakan nikmati waktu Anda!</p>

        <div class="w-full bg-gray-50 rounded-3xl p-5 border border-gray-100 mb-10 shadow-sm">
            <p class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-1">Nomor Pesanan Anda</p>
            <p class="text-2xl font-black text-primary tracking-widest">{{ $order->order_number }}</p>
            <div class="mt-3 pt-3 border-t border-gray-200">
                <p class="text-xs font-semibold text-gray-500 flex items-center justify-center gap-1.5">
                    <i data-lucide="info" class="w-3.5 h-3.5"></i> Status tagihan akan diakumulasi per meja.
                </p>
            </div>
        </div>

        <div class="w-full space-y-4">
            
            <a href="{{ route('customer.status', $order->order_number) }}?table={{ $order->table->table_number }}" class="block w-full bg-primary hover:bg-[#ca8a04] text-white py-4 rounded-2xl font-bold transition shadow-lg shadow-primary/30 flex items-center justify-center gap-2 active:scale-95">
                <i data-lucide="activity" class="w-5 h-5"></i> Pantau Status Pesanan
            </a>
            
            <a href="{{ route('customer.menu', ['table' => $order->table->table_number]) }}" class="block w-full bg-white border-2 border-gray-200 text-secondary py-4 rounded-2xl font-bold hover:bg-gray-50 transition flex items-center justify-center gap-2 active:scale-95">
                <i data-lucide="plus-circle" class="w-5 h-5"></i> Pesan Menu Lainnya
            </a>
            
        </div>
    </div>
</div>

<style>
    .flex-1 { animation: fadeIn 0.5s ease-out forwards; }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection