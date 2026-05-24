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
                        primary: '#EAB308',
                        secondary: '#111827',
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

    <div class="flex w-full min-h-screen overflow-hidden">
        
        <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-yellow-50 to-yellow-100 flex-col items-center justify-center relative p-12">
            
            <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none">
                <div class="absolute -top-24 -left-24 w-96 h-96 bg-primary/20 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-primary/20 rounded-full blur-3xl"></div>
            </div>

            <div class="relative z-10 text-center mb-4">
                <h2 class="text-4xl font-extrabold text-secondary tracking-tight">Self Order System</h2>
                <p class="text-gray-600 mt-2 font-medium">Sistem Manajemen Restoran Terpadu</p>
            </div>

            <div class="relative z-10 w-full max-w-lg">
                <lottie-player 
                    src="{{ asset('Food & Beverage.json') }}" 
                    background="transparent" 
                    speed="1" 
                    style="width: 100%; height: 100%;" 
                    loop 
                    autoplay>
                </lottie-player>
            </div>
        </div>

        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-white relative">
            
            

            <div class="w-full max-w-md">
                <div class="mb-10">
                    <div class="w-14 h-14 bg-primary/10 rounded-2xl flex items-center justify-center mb-6 border border-primary/20">
                        <i data-lucide="utensils" class="w-7 h-7 text-primary"></i>
                    </div>
                    <h1 class="text-3xl font-bold text-secondary mb-2">Welcome Back!</h1>
                    <p class="text-gray-500 text-sm">Masuk ke panel manajemen untuk mengelola bisnis Anda.</p>
                </div>

                <form id="loginForm" class="space-y-6">
                    
                    <div>
                        <label class="block text-sm font-semibold text-secondary mb-2">Username</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i data-lucide="user" class="w-5 h-5 text-gray-400"></i>
                            </div>
                            <input type="text" id="username" name="username" class="w-full pl-11 pr-4 py-3.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary transition outline-none text-secondary bg-gray-50 focus:bg-white" required placeholder="Masukkan username">
                        </div>
                        <p id="err_username" class="text-xs text-red-500 mt-1.5 hidden"></p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-secondary mb-2">Password</label>
                        <div class="relative" x-data="{ show: false }">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i data-lucide="lock" class="w-5 h-5 text-gray-400"></i>
                            </div>
                            <input type="password" id="passwordInput" name="password" class="w-full pl-11 pr-12 py-3.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary transition outline-none text-secondary bg-gray-50 focus:bg-white" required placeholder="••••••••">
                            <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-primary transition">
                                <i data-lucide="eye" id="eyeIcon" class="w-5 h-5"></i>
                            </button>
                        </div>
                        <p id="err_password" class="text-xs text-red-500 mt-1.5 hidden"></p>
                    </div>

                    <button type="submit" id="btnSubmit" class="w-full bg-primary hover:bg-[#ca8a04] text-white font-bold py-3.5 rounded-xl transition duration-200 flex justify-center items-center gap-2 shadow-lg shadow-primary/30 mt-8 active:scale-95">
                        <span id="btnText">Masuk Sekarang</span>
                        <i data-lucide="log-in" id="btnIcon" class="w-5 h-5"></i>
                        <svg id="btnLoader" class="hidden animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                </form>

                <div class="mt-8 text-center">
                    <p class="text-xs text-gray-400">© {{ date('Y') }} Self Order System. All rights reserved.</p>
                </div>
            </div>
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