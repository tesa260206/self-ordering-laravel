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

    <div class="flex-1 flex items-center justify-center w-full my-8 animate-[fadeIn_0.8s_ease-out] pointer-events-none">
        <lottie-player 
            src="{{ asset('Cup of tea.json') }}" 
            background="transparent" 
            speed="1" 
            style="width: 280px; height: 280px;" 
            loop 
            autoplay>
        </lottie-player>
    </div>

    <div class="w-full pb-8 animate-[fadeIn_1s_ease-out]">
        
        <a href="{{ route('customer.menu', ['table' => $table->table_number]) }}" class="w-full bg-primary hover:bg-[#ca8a04] text-white py-4 rounded-2xl text-[17px] font-bold shadow-lg shadow-primary/30 transition-transform active:scale-95 flex items-center justify-center">
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
</style>
@endsection

@push('scripts')
<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
@endpush