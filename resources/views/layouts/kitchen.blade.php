<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Kitchen Display System')</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Poppins', 'sans-serif'] },
                    colors: {
                        kds_bg: '#121212',       // Background Utama Gelap
                        kds_surface: '#1E1E1E',  // Card / Sidebar Gelap
                        kds_border: '#2C2C2C',
                        kds_primary: '#F97316',  // Kuning Accent
                        kds_danger: '#EF4444',   // Merah (Terima Order)
                        kds_success: '#22C55E',  // Hijau (Siap)
                        kds_text: '#E5E7EB',     // Teks Putih Terang
                        kds_text_muted: '#9CA3AF'// Teks Abu
                    }
                }
            }
        }
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #121212; color: #E5E7EB; }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #121212; }
        ::-webkit-scrollbar-thumb { background: #333; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #F97316; }
        
        .swal2-popup { background: #1E1E1E !important; color: #E5E7EB !important; border: 1px solid #2C2C2C; }
        .swal2-title { color: #E5E7EB !important; }
    </style>
</head>
<body class="antialiased overflow-hidden text-sm bg-kds_bg">

    <div x-data="{ sidebarOpen: false }" class="flex h-screen w-full">

        <div x-show="sidebarOpen" style="display: none;" @click="sidebarOpen = false" x-transition.opacity class="fixed inset-0 z-40 bg-black/80 lg:hidden backdrop-blur-sm"></div>

        @include('components.sidebars.kitchen')

        <div class="flex-1 flex flex-col h-full relative overflow-hidden w-full min-w-0">
            
            <div class="lg:hidden flex items-center justify-between bg-kds_surface border-b border-kds_border p-4 shrink-0 shadow-md">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-kds_primary rounded-full flex items-center justify-center">
                        <i data-lucide="utensils-crossed" class="text-black w-4 h-4"></i>
                    </div>
                    <div>
                        <h1 class="font-bold text-kds_text leading-tight text-sm">Self Order System</h1>
                        <p class="text-[10px] text-kds_text_muted">Kitchen Module</p>
                    </div>
                </div>
                <button @click="sidebarOpen = true" class="w-10 h-10 bg-kds_border rounded-lg flex items-center justify-center text-kds_text hover:text-kds_primary transition border border-kds_border/50">
                    <i data-lucide="menu" class="w-5 h-5"></i>
                </button>
            </div>

            <main class="flex-1 overflow-y-auto p-4 md:p-6 custom-scrollbar" id="kds-main-container">
                @yield('content')
            </main>
        </div>
    </div>

    <audio id="notifSound" src="https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3" preload="auto"></audio>

    <script>
        lucide.createIcons();
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

        const Alert = {
            success: function(message) {
                Swal.fire({ icon: 'success', title: 'Berhasil!', text: message, timer: 1500, showConfirmButton: false });
            },
            error: function(message) {
                Swal.fire({ icon: 'error', title: 'Oops...', text: message });
            }
        };
    </script>
    @stack('scripts')
</body>
</html>