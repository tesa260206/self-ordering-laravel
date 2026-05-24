<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
       class="fixed inset-y-0 left-0 z-50 w-[250px] bg-kds_surface border-r border-kds_border flex flex-col transition-transform duration-300 ease-in-out lg:relative lg:translate-x-0 h-full shadow-2xl">
    
    <button @click="sidebarOpen = false" class="lg:hidden absolute top-4 right-4 w-8 h-8 flex items-center justify-center text-kds_text_muted hover:text-kds_danger bg-kds_border rounded-lg transition z-10">
        <i data-lucide="x" class="w-4 h-4"></i>
    </button>

    <div class="p-5 pt-7 shrink-0 border-b border-kds_border relative">
        <span class="bg-kds_primary text-black text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-widest shadow-md shadow-kds_primary/20">Kitchen Module</span>
        <div class="flex items-center gap-3 mt-5">
            {{-- Logo Dinamis dari Settings --}}
            @if(isset($globalSetting) && $globalSetting->logo)
                <div class="w-10 h-10 rounded-xl overflow-hidden border-2 border-kds_primary/30 flex-shrink-0">
                    <img src="{{ Storage::url($globalSetting->logo) }}" alt="Logo" class="w-full h-full object-cover">
                </div>
            @else
                <div class="w-10 h-10 bg-kds_primary rounded-xl flex items-center justify-center shadow-inner flex-shrink-0">
                    <i data-lucide="utensils-crossed" class="text-black w-5 h-5"></i>
                </div>
            @endif
            <div class="min-w-0">
                <h1 class="text-sm font-bold text-kds_text leading-tight truncate">{{ $globalSetting->resto_name ?? 'Self Order System' }}</h1>
                <p class="text-[11px] font-medium text-kds_text_muted">Kitchen Display</p>
            </div>
        </div>
    </div>

    <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-2 custom-scrollbar">
        <a href="{{ route('kitchen.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition group {{ request()->routeIs('kitchen.dashboard') ? 'bg-kds_primary text-black font-bold shadow-md shadow-kds_primary/20' : 'text-kds_text_muted hover:text-kds_text hover:bg-kds_border/50' }}">
            <i data-lucide="inbox" class="w-5 h-5 group-hover:scale-110 transition-transform"></i>
            <span>Incoming Order</span>
        </a>
        
        <a href="{{ route('kitchen.cooking') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition group {{ request()->routeIs('kitchen.cooking') ? 'bg-kds_primary text-black font-bold shadow-md shadow-kds_primary/20' : 'text-kds_text_muted hover:text-kds_text hover:bg-kds_border/50' }}">
            <i data-lucide="flame" class="w-5 h-5 group-hover:scale-110 transition-transform"></i> 
            <span>Sedang Dimasak</span>
        </a>

        <a href="{{ route('kitchen.ready') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition group {{ request()->routeIs('kitchen.ready') ? 'bg-kds_primary text-black font-bold shadow-md shadow-kds_primary/20' : 'text-kds_text_muted hover:text-kds_text hover:bg-kds_border/50' }}">
            <i data-lucide="check-circle" class="w-5 h-5 group-hover:scale-110 transition-transform"></i> 
            <span>Siap Diantar</span>
        </a>

        <a href="{{ route('kitchen.history') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition group {{ request()->routeIs('kitchen.history') ? 'bg-kds_primary text-black font-bold shadow-md shadow-kds_primary/20' : 'text-kds_text_muted hover:text-kds_text hover:bg-kds_border/50' }}">
            <i data-lucide="list-checks" class="w-5 h-5 group-hover:scale-110 transition-transform"></i> 
            <span>Riwayat Masakan</span>
        </a>
    </nav>

    <div class="p-4 border-t border-kds_border shrink-0">
        <div class="flex items-center justify-between p-3 bg-[#1A1A1A] rounded-xl border border-kds_border/50 mb-3 shadow-inner">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-kds_primary flex items-center justify-center border border-kds_border shrink-0">
                    <i data-lucide="user" class="w-4 h-4 text-black"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-bold text-kds_text mb-0.5 truncate">{{ auth()->user()->name ?? 'Koki' }}</p>
                    <p class="text-[10px] text-kds_text_muted capitalize truncate">{{ auth()->user()->getRoleNames()->first() ?? 'Kitchen' }}</p>
                </div>
            </div>
        </div>
        
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full text-left px-4 py-2.5 text-xs font-bold text-kds_text_muted hover:text-white hover:bg-kds_danger rounded-xl transition flex items-center gap-2 group active:scale-95 border border-transparent hover:border-red-500 shadow-sm hover:shadow-red-900/50">
                <i data-lucide="log-out" class="w-4 h-4 group-hover:scale-110 transition-transform"></i> Keluar KDS
            </button>
        </form>
    </div>
</aside>