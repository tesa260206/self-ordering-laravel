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
                    <i data-lucide="utensils-crossed" class="text-white w-5 h-5"></i>
                </div>
            @endif
            <div class="min-w-0">
                <h1 class="text-base font-bold text-secondary leading-tight truncate">{{ $globalSetting->resto_name ?? 'Self Order System' }}</h1>
                <p class="text-[11px] font-medium text-gray-400">Modul Dapur</p>
            </div>
        </div>
        <button @click="sidebarOpen = false" class="lg:hidden ml-auto text-gray-400 hover:text-danger flex-shrink-0">
            <i data-lucide="x" class="w-5 h-5"></i>
        </button>
    </div>

    <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-1 custom-scrollbar">
        
        <a href="{{ route('kitchen.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition group {{ request()->routeIs('kitchen.dashboard') ? 'bg-primary text-white shadow-md shadow-primary/20 font-semibold' : 'text-gray-500 hover:text-primary hover:bg-orange-50 font-medium' }}">
            <i data-lucide="inbox" class="w-5 h-5 group-hover:scale-110 transition-transform"></i>
            <span class="text-sm">Pesanan Masuk</span>
        </a>
        
        <a href="{{ route('kitchen.cooking') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition group {{ request()->routeIs('kitchen.cooking') ? 'bg-primary text-white shadow-md shadow-primary/20 font-semibold' : 'text-gray-500 hover:text-primary hover:bg-orange-50 font-medium' }}">
            <i data-lucide="flame" class="w-5 h-5 group-hover:scale-110 transition-transform"></i> 
            <span class="text-sm">Sedang Dimasak</span>
        </a>

        <a href="{{ route('kitchen.ready') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition group {{ request()->routeIs('kitchen.ready') ? 'bg-primary text-white shadow-md shadow-primary/20 font-semibold' : 'text-gray-500 hover:text-primary hover:bg-orange-50 font-medium' }}">
            <i data-lucide="check-circle" class="w-5 h-5 group-hover:scale-110 transition-transform"></i> 
            <span class="text-sm">Siap Diantar</span>
        </a>

        <a href="{{ route('kitchen.history') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition group {{ request()->routeIs('kitchen.history') ? 'bg-primary text-white shadow-md shadow-primary/20 font-semibold' : 'text-gray-500 hover:text-primary hover:bg-orange-50 font-medium' }}">
            <i data-lucide="list-checks" class="w-5 h-5 group-hover:scale-110 transition-transform"></i> 
            <span class="text-sm">Riwayat Masakan</span>
        </a>
    </nav>

    <div class="p-4 border-t border-gray-100 shrink-0">
        <div class="flex items-center justify-between p-3 bg-gray-50 hover:bg-gray-100 rounded-xl border border-gray-100 transition cursor-pointer mb-3">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center">
                    <i data-lucide="user" class="w-4 h-4 text-primary"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-bold text-secondary leading-none mb-1 truncate">{{ auth()->user()->name ?? 'Koki' }}</p>
                    <p class="text-[11px] text-gray-500 font-medium capitalize truncate">{{ auth()->user()->getRoleNames()->first() ?? 'Kitchen' }}</p>
                </div>
            </div>
            <i data-lucide="chevron-up" class="w-4 h-4 text-gray-400"></i>
        </div>
        
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-gray-500 hover:text-danger hover:bg-red-50 rounded-xl transition group font-medium">
                <i data-lucide="log-out" class="w-5 h-5 group-hover:scale-110 transition-transform text-gray-400 group-hover:text-danger"></i>
                <span class="text-sm">Keluar Sistem</span>
            </button>
        </form>
    </div>
</aside>