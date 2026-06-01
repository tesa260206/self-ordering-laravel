<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - {{ $setting->resto_name ?? 'Self Order' }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Poppins', 'sans-serif'] },
                    colors: {
                        primary: '#F97316',
                        secondary: '#000000',
                        surface: '#FFFFFF',
                        background: '#F9FAFB',
                        danger: '#EF4444',
                    }
                }
            }
        }
    </script>
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body { 
            font-family: 'Poppins', sans-serif; 
            background-color: #F3F4F6; 
            display: flex; 
            justify-content: center; /* Memastikan app selalu di tengah saat dibuka di PC */
        } 
        
        /* Mobile App Wrapper */
        .mobile-wrapper {
            width: 100%;
            max-width: 414px;
            margin: 0 auto;
            min-height: 100vh;
            background-color: #FFFFFF;
            position: relative;
            box-shadow: 0 0 40px rgba(0,0,0,0.05);
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
            padding-bottom: 80px; /* Ruang bernafas agar konten tidak tertutup Bottom Nav */
        }

        * { -webkit-tap-highlight-color: transparent; }

        /* HACK CSS: Dorong Keranjang Belanja Floating sedikit ke atas agar tidak tabrakan dengan Bottom Nav */
        .fixed.bottom-6 { bottom: 90px !important; transition: bottom 0.3s; }
        
        /* Area aman untuk layar iPhone modern (Notch & Home Bar) */
        .pb-safe { padding-bottom: env(safe-area-inset-bottom); }
    </style>
</head>
<body class="antialiased text-secondary">

    <div class="mobile-wrapper" id="app-content">
        @yield('content')

        @php $tableNum = request('table'); @endphp
        
        @if($tableNum)
        <div class="fixed bottom-0 z-[60] w-full max-w-[414px] bg-white border-t border-gray-100 rounded-t-3xl shadow-[0_-5px_40px_rgba(0,0,0,0.06)] pb-safe transition-transform">
            <div class="flex justify-around items-center p-3">
                
                <a href="{{ route('customer.welcome', ['table' => $tableNum]) }}" class="flex-1 flex flex-col items-center gap-1 {{ request()->routeIs('customer.welcome') ? 'text-primary' : 'text-gray-400 hover:text-gray-600' }} transition">
                    <i data-lucide="home" class="{{ request()->routeIs('customer.welcome') ? 'fill-primary/20' : '' }} w-5 h-5"></i>
                    <span class="text-[10px] font-bold">Beranda</span>
                </a>

                <a href="{{ route('customer.menu', ['table' => $tableNum]) }}" class="flex-1 flex flex-col items-center gap-1 {{ request()->routeIs('customer.menu') ? 'text-primary' : 'text-gray-400 hover:text-gray-600' }} transition">
                    <i data-lucide="utensils-crossed" class="{{ request()->routeIs('customer.menu') ? 'fill-primary/20' : '' }} w-5 h-5"></i>
                    <span class="text-[10px] font-bold">Katalog</span>
                </a>

                <a href="javascript:void(0)" onclick="checkLastOrder('{{ $tableNum }}')" class="flex-1 flex flex-col items-center gap-1 {{ request()->routeIs('customer.status') ? 'text-primary' : 'text-gray-400 hover:text-primary' }} transition relative active:scale-95">
                    <i data-lucide="receipt" class="{{ request()->routeIs('customer.status') ? 'fill-primary/20' : '' }} w-5 h-5"></i>
                    <span class="text-[10px] font-bold">Pesanan</span>
                    
                    <span id="nav-order-indicator" class="hidden absolute top-0 right-[calc(50%-16px)] w-2.5 h-2.5 bg-danger rounded-full border-2 border-white animate-pulse"></span>
                </a>

            </div>
        </div>
        @endif
    </div>

    <script>
        lucide.createIcons();

        // ==========================================
        // LOGIKA PINTAR: TRACKING PESANAN TANPA LOGIN
        // ==========================================
        document.addEventListener('DOMContentLoaded', function() {
            
            // 1. Cek apakah URL saat ini adalah halaman Pesanan Sukses atau Detail Status
            const path = window.location.pathname;
            const match = path.match(/\/(success|status)\/(ORD-[a-zA-Z0-9-]+)/);
            
            // 2. Jika pelanggan berada di halaman itu, otomatis rekam "Nomor Pesanan" ke memori HP
            if(match && match[2]) {
                localStorage.setItem('selfOrderLastOrder', match[2]);
            }

            // 3. Jika memori HP menemukan riwayat pesanan, nyalakan titik merah di menu navigasi!
            if (localStorage.getItem('selfOrderLastOrder')) {
                document.getElementById('nav-order-indicator')?.classList.remove('hidden');
            }
        });

        // ==========================================
        // AKSI KETIKA TOMBOL "PESANAN" DITEKAN
        // ==========================================
        function checkLastOrder(table) {
            let lastOrder = localStorage.getItem('selfOrderLastOrder');
            
            if(lastOrder) {
                // Jika pesanan ditemukan, lempar pelanggan kembali ke layar pantau status
                window.location.href = `/customer/status/${lastOrder}?table=${table}`;
            } else {
                // Jika HP tidak merekam pesanan apapun, tampilkan SweetAlert elegan
                Swal.fire({
                    icon: 'info',
                    title: '<span class="text-secondary font-bold">Belum Ada Pesanan</span>',
                    html: '<p class="text-sm text-gray-500">Anda belum memiliki pesanan aktif dari perangkat ini. Yuk lihat katalog menu!</p>',
                    confirmButtonText: 'Lihat Menu',
                    confirmButtonColor: '#F97316',
                    customClass: { popup: 'rounded-3xl shadow-2xl border border-gray-100 p-4' }
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = `/customer/menu?table=${table}`;
                    }
                });
            }
        }
    </script>
    @stack('scripts')
</body>
</html>