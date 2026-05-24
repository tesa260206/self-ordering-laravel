@extends('layouts.customer')
@section('title', 'Pilih Menu - Meja ' . $table->table_number)

@section('content')
<div x-data="menuApp()" class="flex flex-col min-h-screen bg-white pb-24 relative">
    
    <div class="sticky top-0 z-40 bg-white/90 backdrop-blur-md px-5 py-4 flex items-center justify-between border-b border-gray-50">
        <a href="{{ route('customer.welcome', ['table' => $table->table_number]) }}" class="p-2 -ml-2 text-secondary hover:bg-gray-100 rounded-full transition"><i data-lucide="chevron-left" class="w-6 h-6"></i></a>
        <h1 class="text-lg font-bold text-secondary">Meja {{ $table->table_number }}</h1>
        <button class="p-2 -mr-2 text-secondary hover:bg-gray-100 rounded-full transition"><i data-lucide="menu" class="w-6 h-6"></i></button>
    </div>

    <div class="px-5 mt-4 space-y-6">
        
        <div class="flex items-center gap-3">
            <div class="relative flex-1">
                <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                <input type="text" x-model="searchQuery" placeholder="Cari menu favorit..." class="w-full pl-11 pr-4 py-3.5 bg-gray-50 border border-transparent focus:bg-white focus:border-primary rounded-2xl text-sm transition outline-none">
            </div>
        </div>

        <div class="flex overflow-x-auto gap-3 pb-2 custom-scrollbar hide-scroll-indicator -mx-5 px-5">
            <button @click="activeCategory = 'Semua'" :class="activeCategory === 'Semua' ? 'bg-primary text-white shadow-md' : 'bg-gray-50 text-gray-500'" class="px-5 py-2.5 rounded-full text-sm font-semibold transition whitespace-nowrap">Semua</button>
            @foreach($categories as $category)
            <button @click="activeCategory = '{{ $category->name }}'" :class="activeCategory === '{{ $category->name }}' ? 'bg-primary text-white shadow-md' : 'bg-gray-50 text-gray-500'" class="px-5 py-2.5 rounded-full text-sm font-semibold transition whitespace-nowrap">{{ $category->name }}</button>
            @endforeach
        </div>

        <div>
            <h2 class="font-bold text-secondary text-lg mb-4" x-text="activeCategory === 'Semua' ? 'Rekomendasi' : activeCategory"></h2>
            <div class="space-y-4">
                @foreach($menus as $menu)
                <div x-show="(activeCategory === 'Semua' || activeCategory === '{{ $menu->category->name }}') && '{{ strtolower($menu->name) }}'.includes(searchQuery.toLowerCase())" 
                     @click="openDetail({{ $menu->id }}, '{{ addslashes($menu->name) }}', {{ $menu->price }}, '{{ addslashes($menu->description ?? '') }}', '{{ $menu->image ? Storage::url($menu->image) : '' }}')"
                     class="flex items-center gap-4 bg-white p-3 rounded-2xl border border-gray-100 shadow-sm transition-transform active:scale-95 cursor-pointer">
                    
                    <div class="w-20 h-20 rounded-xl bg-gray-50 shrink-0 overflow-hidden relative">
                        @if($menu->image) <img src="{{ Storage::url($menu->image) }}" class="w-full h-full object-cover"> @else <div class="w-full h-full flex items-center justify-center text-gray-300"><i data-lucide="image" class="w-8 h-8"></i></div> @endif
                    </div>

                    <div class="flex-1 min-w-0">
                        <h3 class="font-bold text-secondary text-sm leading-tight mb-1 truncate">{{ $menu->name }}</h3>
                        <p class="font-bold text-primary text-sm">Rp {{ number_format($menu->price, 0, ',', '.') }}</p>
                    </div>

                    <div class="flex items-center justify-end shrink-0 gap-3 min-w-[80px]">
                        <div x-show="getItemQty({{ $menu->id }}) > 0" class="flex items-center gap-3" style="display: none;">
                            <button @click.stop="decreaseQty({{ $menu->id }})" class="w-8 h-8 bg-red-50 text-danger rounded-xl flex items-center justify-center hover:bg-red-100 transition active:scale-90"><i data-lucide="minus" class="w-4 h-4"></i></button>
                            <span class="font-bold text-secondary text-sm min-w-[12px] text-center" x-text="getItemQty({{ $menu->id }})"></span>
                        </div>
                        <button @click.stop="quickAdd({{ $menu->id }}, '{{ addslashes($menu->name) }}', {{ $menu->price }}, '{{ $menu->image ? Storage::url($menu->image) : '' }}')" 
                                :class="getItemQty({{ $menu->id }}) > 0 ? 'w-8 h-8 rounded-xl bg-primary/10 text-primary' : 'w-9 h-9 rounded-xl bg-primary text-white shadow-md'" 
                                class="flex items-center justify-center transition active:scale-90 shrink-0"><i data-lucide="plus" class="w-4 h-4"></i></button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div x-show="cart.length > 0" style="display: none;" class="fixed bottom-6 left-0 right-0 max-w-[414px] mx-auto px-5 z-40">
        <div @click="window.location.href = '{{ route('customer.checkout', ['table' => $table->table_number]) }}'" class="bg-secondary text-white p-4 rounded-2xl shadow-2xl flex items-center justify-between cursor-pointer active:scale-95 transition-transform">
            <div class="flex items-center gap-4">
                <div class="relative"><i data-lucide="shopping-cart" class="w-6 h-6 text-primary"></i><span class="absolute -top-1.5 -right-2 bg-danger text-white text-[10px] font-bold w-4 h-4 flex items-center justify-center rounded-full border-2 border-secondary" x-text="totalCartItems()"></span></div>
                <div><p class="text-sm font-bold leading-tight">Keranjang</p><p class="text-[11px] text-gray-400">Checkout pesanan</p></div>
            </div>
            <div class="flex items-center gap-3"><p class="text-primary font-bold text-lg">Rp <span x-text="formatRupiah(totalCartPrice())"></span></p><i data-lucide="chevron-right" class="w-5 h-5 text-gray-400"></i></div>
        </div>
    </div>

    <div x-show="isDetailOpen" style="display: none;" class="fixed inset-0 z-[60] flex flex-col justify-end max-w-[414px] mx-auto">
        <div @click="closeDetail()" x-show="isDetailOpen" x-transition:enter="transition-opacity ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
        
        <div x-show="isDetailOpen" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0" class="relative bg-white w-full rounded-t-3xl h-[85vh] flex flex-col overflow-hidden">
            
            <div class="h-64 bg-gray-100 relative shrink-0">
                <button @click="closeDetail()" class="absolute top-4 right-4 w-10 h-10 bg-white/50 backdrop-blur-md rounded-full flex items-center justify-center text-secondary z-10 shadow-sm"><i data-lucide="x" class="w-5 h-5"></i></button>
                <img :src="detailData.image || 'https://via.placeholder.com/400x300?text=No+Image'" class="w-full h-full object-cover">
            </div>

            <div class="flex-1 overflow-y-auto p-6 space-y-6 custom-scrollbar pb-48">
                <div>
                    <h2 class="text-2xl font-bold text-secondary mb-1" x-text="detailData.name"></h2>
                    <p class="text-xl font-bold text-primary">Rp <span x-text="formatRupiah(detailData.price)"></span></p>
                    <p class="text-sm text-gray-500 mt-3 leading-relaxed" x-text="detailData.desc || 'Tidak ada deskripsi.'"></p>
                </div>

                <div class="border-t border-gray-100 pt-6">
                    <label class="block text-sm font-bold text-secondary mb-2">Catatan untuk Order <span class="text-xs text-gray-400 font-normal">(Opsional)</span></label>
                    <textarea x-model="detailData.notes" rows="2" placeholder="Contoh: Tidak pedas, tanpa bawang..." class="w-full px-4 py-3 rounded-2xl border border-gray-200 focus:border-primary outline-none text-sm text-secondary bg-gray-50 focus:bg-white transition"></textarea>
                </div>
            </div>

            <div class="absolute bottom-0 left-0 right-0 bg-white border-t border-gray-100 px-5 pt-4 pb-24 flex items-center gap-4 shadow-[0_-10px_20px_rgba(0,0,0,0.03)]">
                
                <div class="flex items-center gap-4 bg-gray-50 px-4 py-3 rounded-2xl border border-gray-100">
                    <button @click="if(detailData.qty > 1) detailData.qty--" class="text-secondary hover:text-danger transition"><i data-lucide="minus" class="w-5 h-5"></i></button>
                    <span class="font-bold text-lg w-6 text-center" x-text="detailData.qty"></span>
                    <button @click="detailData.qty++" class="text-primary hover:text-[#ca8a04] transition"><i data-lucide="plus" class="w-5 h-5"></i></button>
                </div>
                
                <button @click="confirmAddDetail()" class="flex-1 bg-primary text-white py-4 rounded-2xl font-bold shadow-lg shadow-primary/30 active:scale-95 transition">
                    Tambah - Rp <span x-text="formatRupiah(detailData.price * detailData.qty)"></span>
                </button>

            </div>
        </div>
    </div>

</div>

<style>.hide-scroll-indicator::-webkit-scrollbar { display: none; } .hide-scroll-indicator { -ms-overflow-style: none; scrollbar-width: none; }</style>
@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('menuApp', () => ({
            activeCategory: 'Semua', searchQuery: '',
            cart: JSON.parse(localStorage.getItem('selfOrderCart')) || [],
            isDetailOpen: false,
            detailData: { id: null, name: '', price: 0, desc: '', image: '', qty: 1, notes: '' },

            saveCart() { localStorage.setItem('selfOrderCart', JSON.stringify(this.cart)); },
            getItemQty(id) { let item = this.cart.find(i => i.id === id); return item ? item.qty : 0; },

            quickAdd(id, name, price, image) {
                let existing = this.cart.find(i => i.id === id);
                if (existing) { existing.qty++; } else { this.cart.push({ id, name, price, image, qty: 1, notes: '' }); }
                this.saveCart();
            },
            decreaseQty(id) {
                let idx = this.cart.findIndex(i => i.id === id);
                if (idx !== -1) { if (this.cart[idx].qty > 1) { this.cart[idx].qty--; } else { this.cart.splice(idx, 1); } this.saveCart(); }
            },

            // Modal Logic
            openDetail(id, name, price, desc, image) {
                let existing = this.cart.find(i => i.id === id);
                this.detailData = { id, name, price, desc, image, qty: existing ? existing.qty : 1, notes: existing ? existing.notes : '' };
                this.isDetailOpen = true;
            },
            closeDetail() { this.isDetailOpen = false; },
            confirmAddDetail() {
                let idx = this.cart.findIndex(i => i.id === this.detailData.id);
                if (idx !== -1) {
                    this.cart[idx].qty = this.detailData.qty;
                    this.cart[idx].notes = this.detailData.notes;
                } else {
                    this.cart.push({ id: this.detailData.id, name: this.detailData.name, price: this.detailData.price, image: this.detailData.image, qty: this.detailData.qty, notes: this.detailData.notes });
                }
                this.saveCart(); this.closeDetail();
            },

            totalCartItems() { return this.cart.reduce((sum, item) => sum + item.qty, 0); },
            totalCartPrice() { return this.cart.reduce((sum, item) => sum + (item.price * item.qty), 0); },
            formatRupiah(num) { return new Intl.NumberFormat('id-ID').format(num); }
        }));
    });
</script>
@endpush