<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
       class="fixed inset-y-0 left-0 z-50 w-[260px] bg-surface border-r border-gray-100 flex flex-col transition-transform duration-300 ease-in-out lg:relative lg:translate-x-0 h-full shadow-xl lg:shadow-none">
    
    <div class="h-[72px] flex items-center px-6 shrink-0 border-b border-transparent">
        <div class="flex items-center gap-3">
            {{-- Logo Dinamis dari Settings --}}
            @if(isset($globalSetting) && $globalSetting->logo)
                <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-primary/20 shadow-md shadow-primary/20 flex-shrink-0">
                    <img src="{{ Storage::url($globalSetting->logo) }}" alt="Logo" class="w-full h-full object-cover">
                </div>
            @else
                <div class="w-10 h-10 bg-primary rounded-full flex items-center justify-center shadow-md shadow-primary/30 flex-shrink-0">
                    <i data-lucide="utensils" class="text-white w-5 h-5"></i>
                </div>
            @endif
            <div class="min-w-0">
                <h1 class="text-base font-bold text-secondary leading-tight truncate">{{ $globalSetting->resto_name ?? 'Self Order System' }}</h1>
                <p class="text-[11px] font-medium text-gray-400">Admin Panel</p>
            </div>
        </div>
        <button @click="sidebarOpen = false" class="lg:hidden ml-auto text-gray-400 hover:text-danger flex-shrink-0">
            <i data-lucide="x" class="w-5 h-5"></i>
        </button>
    </div>

    <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-1 custom-scrollbar">
        
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition group {{ request()->routeIs('admin.dashboard') ? 'bg-primary text-white shadow-md shadow-primary/20 font-semibold' : 'text-gray-500 hover:text-primary hover:bg-orange-50 font-medium' }}">
            <i data-lucide="home" class="w-5 h-5 group-hover:scale-110 transition-transform"></i>
            <span class="text-sm">Beranda</span>
        </a>

        <div class="pt-5 pb-2">
            <p class="px-4 text-[11px] font-semibold text-gray-400 uppercase tracking-wider mb-2">Data Master</p>
            
            <a href="{{ route('admin.tables.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition group {{ request()->routeIs('admin.tables.*') ? 'bg-primary text-white shadow-md shadow-primary/20 font-semibold' : 'text-gray-500 hover:text-primary hover:bg-orange-50 font-medium' }}">
                <i data-lucide="layout-grid" class="w-5 h-5 group-hover:scale-110 transition-transform"></i>
                <span class="text-sm">Meja</span>
            </a>

            <div x-data="{ openMenu: {{ request()->routeIs('admin.categories.*', 'admin.menus.*') ? 'true' : 'false' }} }">
                <button @click="openMenu = !openMenu" class="w-full flex items-center justify-between px-4 py-2.5 rounded-xl transition group {{ request()->routeIs('admin.categories.*', 'admin.menus.*') ? 'text-primary bg-orange-50 font-semibold' : 'text-gray-500 hover:text-primary hover:bg-orange-50 font-medium' }}">
                    <div class="flex items-center gap-3">
                        <i data-lucide="list-ordered" class="w-5 h-5 group-hover:scale-110 transition-transform"></i>
                        <span class="text-sm">Menu Makanan</span>
                    </div>
                    <i data-lucide="chevron-down" :class="openMenu ? 'rotate-180' : ''" class="w-4 h-4 transition-transform duration-200"></i>
                </button>
                
                <div x-show="openMenu" x-collapse>
                    <div class="pl-12 pr-4 py-2 space-y-1">
                        <a href="{{ route('admin.categories.index') }}" class="block py-2 text-sm transition relative before:absolute before:left-[-16px] before:top-1/2 before:-translate-y-1/2 before:w-1.5 before:h-1.5 before:rounded-full {{ request()->routeIs('admin.categories.*') ? 'text-primary font-bold before:bg-primary' : 'text-gray-500 hover:text-primary before:bg-gray-300 hover:before:bg-primary font-medium' }}">
                            Kategori Menu
                        </a>
                        <a href="{{ route('admin.menus.index') }}" class="block py-2 text-sm transition relative before:absolute before:left-[-16px] before:top-1/2 before:-translate-y-1/2 before:w-1.5 before:h-1.5 before:rounded-full {{ request()->routeIs('admin.menus.*') ? 'text-primary font-bold before:bg-primary' : 'text-gray-500 hover:text-primary before:bg-gray-300 hover:before:bg-primary font-medium' }}">
                            Data Menu
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="pt-3 pb-2">
            <p class="px-4 text-[11px] font-semibold text-gray-400 uppercase tracking-wider mb-2">Transaksi</p>
            
            <a href="{{ route('admin.table-status.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition group {{ request()->routeIs('admin.table-status.*') ? 'bg-primary text-white shadow-md shadow-primary/20 font-semibold' : 'text-gray-500 hover:text-primary hover:bg-orange-50 font-medium' }}">
                <i data-lucide="clipboard-list" class="w-5 h-5 group-hover:scale-110 transition-transform"></i>
                <span class="text-sm">Status Meja</span>
            </a>
            
            <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition group {{ request()->routeIs('admin.orders.*') ? 'bg-primary text-white shadow-md shadow-primary/20 font-semibold' : 'text-gray-500 hover:text-primary hover:bg-orange-50 font-medium' }}">
                <i data-lucide="monitor-play" class="w-5 h-5 group-hover:scale-110 transition-transform"></i>
                <span class="text-sm">Status Pesanan</span>
            </a>
            
            <a href="{{ route('admin.payments.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition group {{ request()->routeIs('admin.payments.*') ? 'bg-primary text-white shadow-md shadow-primary/20 font-semibold' : 'text-gray-500 hover:text-primary hover:bg-orange-50 font-medium' }}">
                <i data-lucide="wallet" class="w-5 h-5 group-hover:scale-110 transition-transform"></i>
                <span class="text-sm">Pembayaran</span>
            </a>
        </div>

        <div class="pt-3 pb-2">
            <p class="px-4 text-[11px] font-semibold text-gray-400 uppercase tracking-wider mb-2">Pengguna</p>
            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition group {{ request()->routeIs('admin.users.*') ? 'bg-primary text-white shadow-md shadow-primary/20 font-semibold' : 'text-gray-500 hover:text-primary hover:bg-orange-50 font-medium' }}">
                <i data-lucide="users" class="w-5 h-5 group-hover:scale-110 transition-transform"></i>
                <span class="text-sm">Pengguna</span>
            </a>
        </div>

        <div class="pt-3 pb-2">
            <p class="px-4 text-[11px] font-semibold text-gray-400 uppercase tracking-wider mb-2">Laporan</p>
            <a href="{{ route('admin.reports.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition group {{ request()->routeIs('admin.reports.index') ? 'bg-primary text-white shadow-md shadow-primary/20 font-semibold' : 'text-gray-500 hover:text-primary hover:bg-orange-50 font-medium' }}">
                <i data-lucide="bar-chart-2" class="w-5 h-5 group-hover:scale-110 transition-transform"></i>
                <span class="text-sm">Laporan Transaksi</span>
            </a>
            <a href="{{ route('admin.reports.charts') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition group {{ request()->routeIs('admin.reports.charts') ? 'bg-primary text-white shadow-md shadow-primary/20 font-semibold' : 'text-gray-500 hover:text-primary hover:bg-orange-50 font-medium' }}">
                <i data-lucide="line-chart" class="w-5 h-5 group-hover:scale-110 transition-transform"></i>
                <span class="text-sm">Analisa Visual</span>
            </a>
        </div>

        <div class="pt-3 pb-2">
            <p class="px-4 text-[11px] font-semibold text-gray-400 uppercase tracking-wider mb-2">Pengaturan</p>
            <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition group {{ request()->routeIs('admin.settings.*') ? 'bg-primary text-white shadow-md shadow-primary/20 font-semibold' : 'text-gray-500 hover:text-primary hover:bg-orange-50 font-medium' }}">
                <i data-lucide="settings" class="w-5 h-5 group-hover:scale-110 transition-transform"></i>
                <span class="text-sm">Pengaturan Sistem</span>
            </a>
        </div>

        <div class="pt-3 pb-6">
            <p class="px-4 text-[11px] font-semibold text-gray-400 uppercase tracking-wider mb-2">Akun</p>
            
            <form method="POST" action="{{ route('logout') }}" id="logout-form-sidebar">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-gray-500 hover:text-danger hover:bg-red-50 rounded-xl transition group font-medium">
                    <i data-lucide="log-out" class="w-5 h-5 group-hover:scale-110 transition-transform text-gray-400 group-hover:text-danger"></i>
                    <span class="text-sm">Keluar Sistem</span>
                </button>
            </form>
        </div>
    </nav>

    <div class="p-4 border-t border-gray-100 shrink-0">
        <div class="flex items-center justify-between p-3 bg-gray-50 hover:bg-gray-100 rounded-xl border border-gray-100 transition cursor-pointer">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center">
                    <i data-lucide="user" class="w-4 h-4 text-primary"></i>
                </div>
                <div>
                    <p class="text-sm font-bold text-secondary leading-none mb-1">{{ auth()->user()->name ?? 'Admin' }}</p>
                    <p class="text-[11px] text-gray-500 font-medium capitalize">{{ auth()->user()->getRoleNames()->first() ?? 'Super Admin' }}</p>
                </div>
            </div>
            <i data-lucide="chevron-up" class="w-4 h-4 text-gray-400"></i>
        </div>
    </div>
</aside>