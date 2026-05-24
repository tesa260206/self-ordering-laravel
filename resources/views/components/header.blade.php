<header class="bg-surface border-b border-gray-100 h-[72px] flex items-center justify-between px-4 md:px-6 shrink-0 z-10 sticky top-0">
    <div class="flex items-center gap-4">
        <button @click="sidebarOpen = true" class="lg:hidden text-gray-500 hover:text-primary transition p-2 bg-gray-50 rounded-lg">
            <i data-lucide="menu" class="w-5 h-5"></i>
        </button>

        {{-- Nama Sistem (Mobile) --}}
        <div class="lg:hidden flex items-center gap-2">
            @if(isset($globalSetting) && $globalSetting->logo)
                <img src="{{ Storage::url($globalSetting->logo) }}" class="h-7 w-7 rounded-full object-cover border border-gray-200" alt="Logo">
            @endif
            <span class="text-sm font-bold text-secondary">{{ $globalSetting->resto_name ?? config('app.name') }}</span>
        </div>
        
    </div>

    
</header>