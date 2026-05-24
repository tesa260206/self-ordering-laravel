@extends('layouts.kitchen')
@section('title', 'Kitchen Display System')

@section('content')
<div class="space-y-6 h-full flex flex-col">

    <div class="flex justify-between items-center bg-kds_surface p-4 rounded-2xl border border-kds_border shrink-0">
        <div>
            <h2 class="text-xl font-bold text-kds_text">Incoming Order</h2>
            <p class="text-xs text-kds_text_muted">Pesanan baru yang masuk dari customer.</p>
        </div>
        <div class="flex items-center gap-4">
            <div class="flex items-center gap-2 text-xs font-medium text-kds_success">
                <span class="w-2 h-2 rounded-full bg-kds_success animate-pulse"></span> Auto Refresh Aktif
            </div>
            <button id="toggleSoundBtn" onclick="toggleSound()" class="w-10 h-10 bg-kds_border rounded-xl flex items-center justify-center text-kds_text hover:text-kds_primary transition border border-kds_border/50">
                <i data-lucide="volume-2" class="w-5 h-5" id="soundIcon"></i>
            </button>
            <button onclick="toggleFullScreen()" class="w-10 h-10 bg-kds_border rounded-xl flex items-center justify-center text-kds_text hover:text-kds_primary transition border border-kds_border/50">
                <i data-lucide="maximize" class="w-5 h-5"></i>
            </button>
        </div>
    </div>

    <div id="kds-board" class="flex-1 flex gap-6 overflow-hidden pb-2" data-order-count="{{ $incomingOrders->count() }}">
        
        <div class="flex-1 flex gap-4 overflow-x-auto custom-scrollbar items-start content-start">
            @forelse($incomingOrders as $order)
                <div class="w-80 shrink-0 bg-kds_surface rounded-2xl border border-kds_border flex flex-col h-fit max-h-full overflow-hidden shadow-lg border-t-4 border-t-kds_danger relative group">
                    
                    <div class="p-4 border-b border-kds_border bg-kds_surface">
                        <div class="flex justify-between items-start mb-3">
                            <span class="bg-kds_danger/20 text-kds_danger text-[10px] font-bold px-2 py-1 rounded-md border border-kds_danger/30">BARU</span>
                            <span class="text-xs font-bold text-kds_text_muted">{{ $order->created_at->format('H:i') }}</span>
                        </div>
                        <div class="flex justify-between items-end mb-1">
                            <h3 class="text-2xl font-black text-kds_text">Meja {{ $order->table->table_number }}</h3>
                            <p class="text-xs font-bold text-kds_danger">{{ $order->created_at->diffForHumans(null, true) }} lalu</p>
                        </div>
                        <p class="text-xs font-medium text-kds_text_muted">{{ $order->order_number }} • {{ $order->customer_name ?? 'Umum' }}</p>
                    </div>

                    <div class="p-4 flex-1 overflow-y-auto custom-scrollbar space-y-4">
                        @foreach($order->items as $item)
                            <div class="flex gap-3">
                                <span class="font-black text-kds_text text-sm">{{ $item->quantity }}x</span>
                                <div>
                                    <p class="font-bold text-kds_text text-sm leading-tight">{{ $item->menu->name }}</p>
                                    @if($item->notes)
                                        <div class="mt-1 flex items-start gap-1">
                                            <i data-lucide="alert-circle" class="w-3 h-3 text-kds_primary shrink-0 mt-0.5"></i>
                                            <p class="text-[11px] text-kds_primary italic font-medium">{{ $item->notes }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="p-4 border-t border-kds_border bg-[#1A1A1A] flex justify-between items-center">
                        <p class="text-xs text-kds_text_muted">Total Item: <span class="font-bold text-kds_text">{{ $order->items->sum('quantity') }}</span></p>
                        <button onclick="processOrder({{ $order->id }}, 'cooking')" class="bg-kds_danger hover:bg-red-600 text-white text-xs font-bold px-5 py-2.5 rounded-lg transition active:scale-95 shadow-md shadow-red-900/50">
                            Terima Order
                        </button>
                    </div>
                </div>
            @empty
                <div class="w-full h-full flex flex-col items-center justify-center text-kds_text_muted border-2 border-dashed border-kds_border rounded-2xl">
                    <i data-lucide="coffee" class="w-12 h-12 mb-3 opacity-50"></i>
                    <p class="font-medium">Tidak ada pesanan baru</p>
                </div>
            @endforelse
        </div>

        <div class="w-96 shrink-0 flex flex-col gap-6 overflow-y-auto custom-scrollbar pr-2">
            
            <div class="bg-kds_surface rounded-2xl border border-kds_border flex flex-col overflow-hidden shadow-lg border-t-4 border-t-kds_primary">
                <div class="p-4 border-b border-kds_border flex justify-between items-center bg-[#1A1A1A]">
                    <div class="flex items-center gap-2">
                        <h3 class="font-bold text-kds_text">Sedang Dimasak</h3>
                        <span class="bg-kds_primary/20 text-kds_primary text-[10px] font-bold px-2 py-0.5 rounded-md">{{ $cookingOrders->count() }}</span>
                    </div>
                </div>
                <div class="flex-1 overflow-y-auto custom-scrollbar p-2 space-y-2 max-h-80">
                    @forelse($cookingOrders as $order)
                        <div class="bg-[#1A1A1A] border border-kds_border p-3 rounded-xl flex items-center justify-between group hover:border-kds_primary/50 transition">
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-xs font-black text-kds_text">Meja {{ $order->table->table_number }}</span>
                                    <span class="text-[10px] text-kds_text_muted">{{ $order->created_at->format('H:i') }}</span>
                                </div>
                                <p class="text-[10px] text-kds_text_muted truncate w-32">{{ $order->items->sum('quantity') }} Item</p>
                            </div>
                            <button onclick="processOrder({{ $order->id }}, 'ready')" class="bg-kds_primary/20 hover:bg-kds_primary text-kds_primary hover:text-black text-[10px] font-bold px-4 py-2 rounded-lg transition active:scale-95 border border-kds_primary/30">
                                Selesai Masak
                            </button>
                        </div>
                    @empty
                        <div class="p-4 text-center text-xs text-kds_text_muted">Tidak ada proses memasak.</div>
                    @endforelse
                </div>
            </div>

            <div class="bg-kds_surface rounded-2xl border border-kds_border flex flex-col overflow-hidden shadow-lg border-t-4 border-t-kds_success">
                <div class="p-4 border-b border-kds_border flex justify-between items-center bg-[#1A1A1A]">
                    <div class="flex items-center gap-2">
                        <h3 class="font-bold text-kds_text">Siap Diantar</h3>
                        <span class="bg-kds_success/20 text-kds_success text-[10px] font-bold px-2 py-0.5 rounded-md">{{ $readyOrders->count() }}</span>
                    </div>
                </div>
                <div class="flex-1 overflow-y-auto custom-scrollbar p-2 space-y-2 max-h-80">
                    @forelse($readyOrders as $order)
                        <div class="bg-[#1A1A1A] border border-kds_border p-3 rounded-xl flex items-center justify-between">
                            <div>
                                <span class="text-xs font-black text-kds_text">Meja {{ $order->table->table_number }}</span>
                                <p class="text-[10px] text-kds_text_muted mt-0.5">{{ $order->order_number }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <i data-lucide="check-circle" class="w-4 h-4 text-kds_success"></i>
                                <span class="text-[10px] font-bold text-kds_success">SIAP</span>
                            </div>
                        </div>
                    @empty
                        <div class="p-4 text-center text-xs text-kds_text_muted">Semua sudah diantar.</div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let isSoundEnabled = false;
    let prevOrderCount = {{ $incomingOrders->count() }};
    const notifSound = document.getElementById('notifSound');

    // Toggle Izin Suara Notifikasi (Browser Policy Requires User Interaction)
    function toggleSound() {
        isSoundEnabled = !isSoundEnabled;
        const icon = document.getElementById('soundIcon');
        if(isSoundEnabled) {
            icon.setAttribute('data-lucide', 'volume-2');
            icon.classList.add('text-kds_primary');
            notifSound.play().catch(e => console.log('Init sound play')); // Pancing play
        } else {
            icon.setAttribute('data-lucide', 'volume-x');
            icon.classList.remove('text-kds_primary');
        }
        lucide.createIcons();
    }

    // Toggle Fullscreen Layar Dapur
    function toggleFullScreen() {
        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen().catch(err => {
                alert(`Error: ${err.message}`);
            });
        } else {
            if (document.exitFullscreen) { document.exitFullscreen(); }
        }
    }

    // AJAX Action Update Status
    function processOrder(orderId, newStatus) {
        $.ajax({
            url: `/kitchen/orders/${orderId}/status`,
            type: 'PUT',
            data: { status: newStatus },
            success: function(res) {
                refreshBoardSilently();
            },
            error: function() {
                Alert.error('Gagal memperbarui status pesanan.');
            }
        });
    }

    // Polling Auto-Refresh KDS
    function refreshBoardSilently() {
        let originalGlobalAjaxLoader = $.ajaxSettings.global;
        $.ajaxSetup({ global: false }); 

        $('#kds-board').load(window.location.href + ' #kds-board > *', function() {
            lucide.createIcons();
            
            // Cek jika ada pesanan baru untuk play sound
            let currentCount = parseInt($('#kds-board').attr('data-order-count'));
            if(currentCount > prevOrderCount && isSoundEnabled) {
                notifSound.play();
            }
            prevOrderCount = currentCount;
        });

        $.ajaxSetup({ global: originalGlobalAjaxLoader });
    }

    $(document).ready(function() {
        // Refresh setiap 10 Detik
        setInterval(refreshBoardSilently, 10000); 
        
        // Aktifkan suara default (opsional, tergantung browser)
        // document.getElementById('toggleSoundBtn').click(); 
    });
</script>
@endpush