@extends('layouts.app')

@section('title', 'Menu Makanan')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-secondary">Data Menu</h2>
            <p class="text-sm text-gray-500">Kelola master data menu beserta harganya.</p>
        </div>
        <button onclick="openModal('create')" class="bg-primary hover:bg-[#ca8a04] text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition shadow-md shadow-primary/30 flex items-center gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i>
            Tambah Menu
        </button>
    </div>

    <div id="data-container" class="bg-surface border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-gray-50/50 text-gray-500">
                    <tr>
                        <th class="px-6 py-4 font-semibold w-16">No</th>
                        <th class="px-6 py-4 font-semibold">Menu Item</th>
                        <th class="px-6 py-4 font-semibold">Kategori</th>
                        <th class="px-6 py-4 font-semibold">Harga</th>
                        <th class="px-6 py-4 font-semibold text-center">Status</th>
                        <th class="px-6 py-4 font-semibold text-center w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($menus as $index => $menu)
                    <tr class="hover:bg-gray-50/50 transition group">
                        <td class="px-6 py-4 text-gray-500">{{ $index + 1 }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg overflow-hidden border border-gray-200 bg-gray-50 shrink-0">
                                    @if($menu->image)
                                        <img src="{{ Storage::url($menu->image) }}" alt="{{ $menu->name }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                                            <i data-lucide="image" class="w-5 h-5"></i>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-bold text-secondary">{{ $menu->name }}</p>
                                    <p class="text-xs text-gray-400 truncate w-48">{{ $menu->description ?? '-' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-500 font-medium">{{ $menu->category->name }}</td>
                        <td class="px-6 py-4 font-bold text-secondary">Rp {{ number_format($menu->price, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-center">
                            {{-- Toggle Switch Status --}}
                            <button 
                                onclick="toggleMenu({{ $menu->id }}, this)" 
                                data-id="{{ $menu->id }}"
                                data-available="{{ $menu->is_available ? '1' : '0' }}"
                                class="relative inline-flex items-center h-6 rounded-full w-11 transition-colors focus:outline-none {{ $menu->is_available ? 'bg-success' : 'bg-gray-300' }}"
                                title="{{ $menu->is_available ? 'Nonaktifkan Menu' : 'Aktifkan Menu' }}">
                                <span class="inline-block w-4 h-4 bg-white rounded-full shadow transform transition-transform {{ $menu->is_available ? 'translate-x-6' : 'translate-x-1' }}"></span>
                            </button>
                            <p class="text-[10px] mt-1 font-semibold {{ $menu->is_available ? 'text-success' : 'text-gray-400' }}">
                                {{ $menu->is_available ? 'Tersedia' : 'Kosong' }}
                            </p>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="openModal('edit', {{ $menu->id }})" class="p-1.5 text-blue-500 bg-blue-50 hover:bg-blue-500 hover:text-white transition rounded-lg" title="Edit">
                                    <i data-lucide="edit-2" class="w-4 h-4"></i>
                                </button>
                                <button onclick="deleteData({{ $menu->id }})" class="p-1.5 text-danger bg-red-50 hover:bg-danger hover:text-white transition rounded-lg" title="Hapus">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-3 text-gray-300">
                                    <i data-lucide="utensils" class="w-8 h-8"></i>
                                </div>
                                <p class="font-medium text-secondary">Belum ada data menu</p>
                                <p class="text-sm">Silakan tambahkan menu makanan/minuman.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="menuModal" class="fixed inset-0 z-[100] bg-secondary/60 backdrop-blur-sm hidden flex-col items-center justify-center p-4">
    <div class="bg-surface w-full max-w-2xl rounded-2xl shadow-2xl border border-gray-100 overflow-hidden transform scale-95 opacity-0 transition-all duration-300 flex flex-col max-h-[90vh]" id="modalContent">
        
        <div class="flex justify-between items-center p-5 border-b border-gray-100 bg-gray-50/50 shrink-0">
            <h3 class="text-lg font-bold text-secondary flex items-center gap-2">
                <i data-lucide="utensils" class="w-5 h-5 text-primary"></i>
                <span id="modalTitle">Tambah Menu</span>
            </h3>
            <button type="button" onclick="closeModal()" class="text-gray-400 hover:text-danger hover:bg-red-50 p-1.5 rounded-lg transition"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>

        <form id="menuForm" class="overflow-y-auto custom-scrollbar" enctype="multipart/form-data">
            <div class="p-5 space-y-5">
                <input type="hidden" id="menu_id" name="id">
                <input type="hidden" id="method_spoofing" name="_method" value="POST">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-secondary mb-1.5">Nama Menu <span class="text-danger">*</span></label>
                        <input type="text" id="name" name="name" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary transition outline-none text-sm text-secondary bg-gray-50 focus:bg-white">
                        <span class="text-xs text-danger hidden mt-1" id="err_name"></span>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-secondary mb-1.5">Kategori <span class="text-danger">*</span></label>
                        <select id="category_id" name="category_id" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary transition outline-none text-sm text-secondary bg-gray-50 focus:bg-white">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-secondary mb-1.5">Harga (Rp) <span class="text-danger">*</span></label>
                        <input type="number" id="price" name="price" required min="0" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary transition outline-none text-sm text-secondary bg-gray-50 focus:bg-white">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-secondary mb-1.5">Status Ketersediaan <span class="text-danger">*</span></label>
                        <select id="is_available" name="is_available" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary transition outline-none text-sm text-secondary bg-gray-50 focus:bg-white">
                            <option value="1">Tersedia</option>
                            <option value="0">Kosong / Habis</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-secondary mb-1.5">Deskripsi Singkat</label>
                    <textarea id="description" name="description" rows="2" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary transition outline-none text-sm text-secondary bg-gray-50 focus:bg-white"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-secondary mb-1.5">Foto Menu <span class="text-xs text-gray-400 font-normal">(Opsional, max 2MB)</span></label>
                    <input type="file" id="image" name="image" accept="image/*" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary transition outline-none text-sm text-secondary bg-gray-50 focus:bg-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
                </div>
            </div>

            <div class="p-5 border-t border-gray-100 bg-gray-50/50 flex justify-end gap-3 shrink-0">
                <button type="button" onclick="closeModal()" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-gray-600 bg-white border border-gray-200 hover:bg-gray-50 transition">Batal</button>
                <button type="submit" id="btnSave" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-white bg-primary hover:bg-[#ca8a04] transition shadow-md shadow-primary/30 flex items-center gap-2">
                    <i data-lucide="save" class="w-4 h-4"></i> Simpan Menu
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let isEditMode = false;

    function refreshGrid() {
        $('#data-container').load(window.location.href + ' #data-container > *', function() {
            lucide.createIcons();
        });
    }

    function openModal(type, id = null) {
        isEditMode = (type === 'edit');
        $('#err_name').addClass('hidden').text('');
        $('#menuForm')[0].reset();
        
        if (isEditMode) {
            $('#modalTitle').text('Edit Menu');
            $('#menu_id').val(id);
            $('#method_spoofing').val('PUT');
            
            $.get(`/admin/menus/${id}/edit`, function(data) {
                $('#name').val(data.name);
                $('#category_id').val(data.category_id);
                $('#price').val(data.price);
                $('#is_available').val(data.is_available);
                $('#description').val(data.description);
            });
        } else {
            $('#modalTitle').text('Tambah Menu Baru');
            $('#menu_id').val('');
            $('#method_spoofing').val('POST');
        }

        $('#menuModal').removeClass('hidden').addClass('flex');
        setTimeout(() => { $('#modalContent').removeClass('scale-95 opacity-0').addClass('scale-100 opacity-100'); }, 10);
    }

    function closeModal() {
        $('#modalContent').removeClass('scale-100 opacity-100').addClass('scale-95 opacity-0');
        setTimeout(() => { $('#menuModal').removeClass('flex').addClass('hidden'); }, 300);
    }

    $('#menuForm').submit(function(e) {
        e.preventDefault();
        $('#err_name').addClass('hidden');
        
        let id  = $('#menu_id').val();
        let url = isEditMode ? `/admin/menus/${id}` : `/admin/menus`;
        
        let formData = new FormData(this);
        
        let btn = $('#btnSave');
        let originalText = btn.html();
        btn.prop('disabled', true).html('<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i> Memproses...');
        lucide.createIcons();

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                closeModal();
                Alert.success(response.message);
                refreshGrid();
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    if(errors.name) { $('#err_name').removeClass('hidden').text(errors.name[0]); }
                } else {
                    Alert.error('Terjadi kesalahan pada sistem.');
                }
            },
            complete: function() {
                btn.prop('disabled', false).html(originalText);
                lucide.createIcons();
            }
        });
    });

    function toggleMenu(id, btn) {
        let isAvailable = btn.getAttribute('data-available') === '1';

        $.ajax({
            url: `/admin/menus/${id}/toggle`,
            type: 'POST',
            data: { _method: 'PATCH' },
            success: function(response) {
                Alert.success(response.message);
                // Update UI tanpa reload
                let newState = response.is_available;
                btn.setAttribute('data-available', newState ? '1' : '0');
                if (newState) {
                    btn.classList.remove('bg-gray-300');
                    btn.classList.add('bg-success');
                    btn.querySelector('span').classList.remove('translate-x-1');
                    btn.querySelector('span').classList.add('translate-x-6');
                    btn.nextElementSibling.textContent = 'Tersedia';
                    btn.nextElementSibling.className = 'text-[10px] mt-1 font-semibold text-success';
                } else {
                    btn.classList.remove('bg-success');
                    btn.classList.add('bg-gray-300');
                    btn.querySelector('span').classList.remove('translate-x-6');
                    btn.querySelector('span').classList.add('translate-x-1');
                    btn.nextElementSibling.textContent = 'Kosong';
                    btn.nextElementSibling.className = 'text-[10px] mt-1 font-semibold text-gray-400';
                }
            },
            error: function() {
                Alert.error('Gagal mengubah status menu.');
            }
        });
    }

    function deleteData(id) {
        Alert.confirmDelete(function() {
            $.ajax({
                url: `/admin/menus/${id}`,
                type: 'DELETE',
                success: function(response) {
                    Alert.success(response.message);
                    refreshGrid();
                },
                error: function() {
                    Alert.error('Gagal menghapus menu.');
                }
            });
        });
    }
</script>
@endpush