@extends('layouts.customer')

@section('title', 'Selamat Datang di Meja ' . $table->table_number)

@section('content')
<div class="flex flex-col items-center justify-between p-6 text-center min-h-screen bg-white">
    
    <div class="mt-16 w-full animate-[fadeIn_0.5s_ease-out]">
        <h2 class="text-[22px] font-bold text-secondary mb-1 tracking-tight">Selamat Datang!</h2>
        
        <h1 class="text-4xl font-black text-secondary leading-none mb-4 tracking-tight">
            Meja <span class="text-primary">{{ $table->table_number }}</span>
        </h1>
        
        <p class="text-gray-500 text-[15px] font-medium leading-relaxed max-w-[200px] mx-auto">
            Silakan pilih menu<br>favorit Anda
        </p>
    </div>

    <div class="flex-1 flex items-center justify-center w-full mt-4 mb-4 animate-[fadeIn_0.8s_ease-out] pointer-events-none">
        <lottie-player 
            src="{{ asset('Cup of tea.json') }}" 
            background="transparent" 
            speed="1" 
            style="width: 200px; height: 200px;" 
            loop 
            autoplay>
        </lottie-player>
    </div>

    {{-- Menu Terlaris (Marquee) --}}
    @if(isset($bestSellers) && $bestSellers->count() > 0)
    <div class="w-full overflow-hidden mb-8 animate-[fadeIn_0.9s_ease-out]">
        <div class="mb-3 text-left px-6 flex items-center gap-2">
            <i data-lucide="star" class="w-4 h-4 text-primary fill-primary"></i>
            <span class="text-xs font-bold text-secondary uppercase tracking-wider">Menu Best Seller</span>
        </div>
        
        <div class="flex w-max animate-marquee">
            {{-- Bagian Pertama --}}
            <div class="flex gap-3 px-3 w-max">
                @foreach($bestSellers as $menu)
                    <div class="flex items-center gap-3 bg-white p-2.5 rounded-2xl border border-gray-100 shadow-sm w-[210px] flex-shrink-0">
                        @if($menu->image)
                            <img src="{{ Storage::url($menu->image) }}" class="w-12 h-12 rounded-2xl object-cover" alt="{{ $menu->name }}">
                        @else
                            <div class="w-12 h-12 rounded-2xl bg-gray-100 flex items-center justify-center text-gray-400">
                                <i data-lucide="image" class="w-5 h-5"></i>
                            </div>
                        @endif
                        <div class="flex-1 overflow-hidden text-left">
                            <h4 class="text-[13px] font-bold text-secondary truncate">{{ $menu->name }}</h4>
                            <p class="text-[11px] font-semibold text-primary mt-0.5">Rp {{ number_format($menu->price, 0, ',', '.') }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
            {{-- Duplikat untuk animasi seamless berjalan terus menerus --}}
            <div class="flex gap-3 px-3 w-max" aria-hidden="true">
                @foreach($bestSellers as $menu)
                    <div class="flex items-center gap-3 bg-white p-2.5 rounded-2xl border border-gray-100 shadow-sm w-[210px] flex-shrink-0">
                        @if($menu->image)
                            <img src="{{ Storage::url($menu->image) }}" class="w-12 h-12 rounded-2xl object-cover" alt="{{ $menu->name }}">
                        @else
                            <div class="w-12 h-12 rounded-2xl bg-gray-100 flex items-center justify-center text-gray-400">
                                <i data-lucide="image" class="w-5 h-5"></i>
                            </div>
                        @endif
                        <div class="flex-1 overflow-hidden text-left">
                            <h4 class="text-[13px] font-bold text-secondary truncate">{{ $menu->name }}</h4>
                            <p class="text-[11px] font-semibold text-primary mt-0.5">Rp {{ number_format($menu->price, 0, ',', '.') }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <div class="w-full pb-8 animate-[fadeIn_1s_ease-out] px-6">
        
        <a href="{{ route('customer.menu', ['table' => $table->table_number]) }}" class="w-full bg-primary hover:bg-[#EA580C] text-white py-4 rounded-2xl text-[17px] font-bold shadow-lg shadow-primary/30 transition-transform active:scale-95 flex items-center justify-center">
            Lihat Menu
        </a>
        
        <div class="flex items-center justify-center gap-2 mt-8">
            <div class="w-5 h-5 rounded-full border-2 border-primary flex items-center justify-center opacity-80">
                <div class="w-2 h-2 rounded-full bg-primary"></div>
            </div>
            <p class="text-[11px] font-bold text-gray-400">
                Powered by <span class="text-secondary">Self Order System</span>
            </p>
        </div>
        
    </div>
</div>

<style>
    /* Animasi muncul dari bawah agar transisi halaman terasa halus layaknya aplikasi mobile */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Animasi berjalan untuk marquee best seller */
    @keyframes marquee {
        0% { transform: translateX(0%); }
        100% { transform: translateX(-50%); }
    }
    
    .animate-marquee {
        animation: marquee 25s linear infinite;
    }
    
    .animate-marquee:hover {
        animation-play-state: paused;
    }
</style>
@endsection

@push('scripts')
<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
@endpush