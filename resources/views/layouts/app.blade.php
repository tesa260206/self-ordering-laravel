<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'Self Ordering QR'))</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                    },
                    colors: {
                        primary: '#885d3e',   // Kuning tua elegan
                        secondary: '#000000', // Hitam gelap
                        surface: '#FFFFFF',   // Putih
                        background: '#F9FAFB',// Abu terang
                        success: '#22C55E',   // Hijau
                        danger: '#EF4444',    // Merah
                        warning: '#885d3e',   // Orange
                    }
                }
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #F9FAFB; }
        
        /* Glassmorphism utility */
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #885d3e; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #885d3e; }

        /* AJAX Loading Overlay (Default hidden) */
        #ajax-loader { display: none; }
    </style>

    
</head>

<body class="bg-background text-secondary font-sans antialiased flex h-screen overflow-hidden" x-data="{ sidebarOpen: false }">

    <div x-show="sidebarOpen" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="sidebarOpen = false" 
         class="fixed inset-0 bg-secondary/50 z-40 lg:hidden backdrop-blur-sm" style="display: none;"></div>

    @auth
        @if(auth()->user()->hasRole('admin'))
            @include('components.sidebars.admin')
        @elseif(auth()->user()->hasRole('cashier'))
            @include('components.sidebars.cashier')
        @elseif(auth()->user()->hasRole('kitchen'))
            @include('components.sidebars.kitchen')
        @endif
    @endauth

    <div class="flex-1 flex flex-col h-full relative overflow-hidden w-full">
        
        @auth
            @include('components.header')
        @endauth

        <main class="flex-1 overflow-y-auto p-4 md:p-6 custom-scrollbar">
            @yield('content')
        </main>
    </div>

    <div id="ajax-loader" class="fixed inset-0 z-[9999] bg-secondary/50 backdrop-blur-sm flex justify-center items-center">
        <div class="animate-spin rounded-full h-16 w-16 border-t-4 border-b-4 border-primary"></div>
    </div>

    <script>
        // Init Lucide Icons
        lucide.createIcons();

        // Global AJAX Setup for CSRF Token
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Global AJAX Loader handlers
        $(document).ajaxStart(function() {
            $('#ajax-loader').fadeIn(150);
        }).ajaxStop(function() {
            $('#ajax-loader').fadeOut(150);
        });

        // Global SweetAlert Helpers — Toast mode untuk success/error
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });

        const Alert = {
            success: function(message) {
                Toast.fire({ icon: 'success', title: message });
            },
            error: function(message) {
                Toast.fire({ icon: 'error', title: message });
            },
            confirmDelete: function(callback) {
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#EF4444',
                    cancelButtonColor: '#000000',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) { callback(); }
                });
            }
        };
    </script>

    @stack('scripts')
</body>
</html>