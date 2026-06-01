<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login | Self Ordering App</title>
    
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
                    }
                }
            }
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>

    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-background min-h-screen flex items-center justify-center">

    <div class="w-full max-w-[420px] bg-white p-8 sm:p-10 rounded-3xl shadow-2xl shadow-gray-200/50 border border-gray-100 relative overflow-hidden my-8">
        
        {{-- Animasi Lottie Kecil di Atas --}}
        <div class="w-40 h-40 mx-auto mb-4 relative">
            <div class="absolute inset-0 bg-primary/20 rounded-full blur-2xl scale-150"></div>
            <lottie-player 
                src="{{ asset('Food & Beverage.json') }}" 
                background="transparent" 
                speed="1" 
                style="width: 100%; height: 100%;" 
                loop 
                autoplay
                class="relative z-10 scale-125">
            </lottie-player>
        </div>

        {{-- Header / Judul --}}
        <div class="text-center mb-8 relative z-10">
            @php $setting = \App\Models\Setting::first(); @endphp
            <h1 class="text-[28px] font-extrabold text-secondary tracking-tight mb-1">{{ $setting->resto_name ?? 'Self Order System' }}</h1>
            <p class="text-gray-500 text-[13px] font-medium leading-relaxed">Masuk ke panel manajemen untuk mengelola bisnis restoran Anda.</p>
        </div>

        {{-- Form Login --}}
        <form id="loginForm" class="space-y-5 relative z-10">
            
            <div>
                <label class="block text-[13px] font-bold text-secondary mb-1.5">Username</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i data-lucide="user" class="w-[18px] h-[18px] text-gray-400"></i>
                    </div>
                    <input type="text" id="username" name="username" class="w-full pl-11 pr-4 py-3.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary transition outline-none text-sm text-secondary bg-gray-50 focus:bg-white font-medium" required placeholder="Masukkan username">
                </div>
                <p id="err_username" class="text-[11px] font-bold text-red-500 mt-1.5 hidden"></p>
            </div>

            <div>
                <label class="block text-[13px] font-bold text-secondary mb-1.5">Password</label>
                <div class="relative" x-data="{ show: false }">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i data-lucide="lock" class="w-[18px] h-[18px] text-gray-400"></i>
                    </div>
                    <input type="password" id="passwordInput" name="password" class="w-full pl-11 pr-12 py-3.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary transition outline-none text-sm text-secondary bg-gray-50 focus:bg-white font-medium" required placeholder="••••••••">
                    <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-primary transition">
                        <i data-lucide="eye" id="eyeIcon" class="w-5 h-5"></i>
                    </button>
                </div>
                <p id="err_password" class="text-[11px] font-bold text-red-500 mt-1.5 hidden"></p>
            </div>

            <button type="submit" id="btnSubmit" class="w-full bg-primary hover:bg-[#EA580C] text-white font-bold py-3.5 rounded-xl transition duration-200 flex justify-center items-center gap-2 shadow-lg shadow-primary/30 mt-8 active:scale-95 text-[15px]">
                <span id="btnText">Masuk Sekarang</span>
                <i data-lucide="log-in" id="btnIcon" class="w-[18px] h-[18px]"></i>
                <svg id="btnLoader" class="hidden animate-spin h-[18px] w-[18px] text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </button>
        </form>

        <div class="mt-8 text-center relative z-10">
            <p class="text-[11px] text-gray-400 font-bold uppercase tracking-wider">© {{ date('Y') }} Self Order System</p>
        </div>
    </div>

    <script>
        // Inisialisasi ikon Lucide
        lucide.createIcons();

        // Fitur Lihat/Sembunyikan Password
        $('#togglePassword').click(function() {
            let input = $('#passwordInput');
            let icon = $('#eyeIcon');
            
            if (input.attr('type') === 'password') {
                input.attr('type', 'text');
                icon.attr('data-lucide', 'eye-off');
            } else {
                input.attr('type', 'password');
                icon.attr('data-lucide', 'eye');
            }
            lucide.createIcons();
        });

        // Setup CSRF Token
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

        // Global SweetAlert Toast Setup
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

        // Proses Login AJAX
        $('#loginForm').submit(function(e) {
            e.preventDefault();
            
            let btn = $('#btnSubmit');
            $('#btnText').text('Memproses...');
            $('#btnIcon').addClass('hidden');
            $('#btnLoader').removeClass('hidden');
            btn.prop('disabled', true);
            
            // Bersihkan error sebelumnya
            $('#err_username').addClass('hidden').text('');
            $('#err_password').addClass('hidden').text('');
            $('#username, #passwordInput').removeClass('border-red-500').addClass('border-gray-200');

            $.ajax({
                url: "{{ route('login.post') }}",
                type: "POST",
                data: $(this).serialize(),
                success: function(response) {
                    if(response.success) {
                        Toast.fire({
                            icon: 'success',
                            title: 'Login Berhasil, mengarahkan...'
                        });
                        setTimeout(() => {
                            window.location.href = response.redirect;
                        }, 1000);
                    }
                },
                error: function(xhr) {
                    // Kembalikan tombol ke keadaan semula
                    $('#btnText').text('Masuk Sekarang');
                    $('#btnIcon').removeClass('hidden');
                    $('#btnLoader').addClass('hidden');
                    btn.prop('disabled', false);
                    
                    if (xhr.status === 422) {
                        // Error Validasi (Required, dsb)
                        let errors = xhr.responseJSON.errors;
                        if(errors.username) {
                            $('#err_username').removeClass('hidden').text(errors.username[0]);
                            $('#username').removeClass('border-gray-200').addClass('border-red-500');
                        }
                        if(errors.password) {
                            $('#err_password').removeClass('hidden').text(errors.password[0]);
                            $('#passwordInput').removeClass('border-gray-200').addClass('border-red-500');
                        }
                    } else if (xhr.status === 401) {
                        // Error Auth (Salah username / password / diblokir)
                        let msg = xhr.responseJSON.message || 'Username atau password salah.';
                        $('#err_password').removeClass('hidden').text(msg);
                        $('#passwordInput, #username').removeClass('border-gray-200').addClass('border-red-500');
                        
                        Toast.fire({
                            icon: 'error',
                            title: 'Login Gagal',
                            text: msg
                        });
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan Server'
                        });
                    }
                }
            });
        });
    </script>
</body>
</html>