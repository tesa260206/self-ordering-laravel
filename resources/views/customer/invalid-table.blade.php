@extends('layouts.customer')

@section('title', 'QR Code Tidak Valid')

@section('content')
<div class="flex-1 flex flex-col items-center justify-center p-8 text-center h-screen">
    
    @if(isset($setting->logo))
        <img src="{{ Storage::url($setting->logo) }}" alt="Logo" class="h-10 mx-auto mb-10 object-contain opacity-50 relative -top-10">
    @endif

    <div class="w-24 h-24 bg-red-50 rounded-full flex items-center justify-center mb-6 animate-[bounce_2s_infinite]">
        <i data-lucide="scan-line" class="w-10 h-10 text-danger"></i>
    </div>

    <h1 class="text-2xl font-bold text-secondary mb-2">QR Code Tidak Valid</h1>
    <p class="text-gray-500 text-sm mb-8 px-4">
        Maaf, kami tidak dapat menemukan data meja dari QR Code yang Anda scan. Silakan coba scan ulang QR Code yang ada di meja Anda.
    </p>

    <div class="p-4 bg-yellow-50 border border-yellow-100 rounded-2xl w-full text-left flex gap-3">
        <i data-lucide="info" class="w-5 h-5 text-warning shrink-0 mt-0.5"></i>
        <p class="text-xs text-yellow-800">
            Jika masalah berlanjut, mohon informasikan kepada pelayan atau kasir kami untuk mendapatkan bantuan.
        </p>
    </div>

</div>
@endsection