@extends('layouts.app')
@section('title', 'Kategori Menu')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-secondary">Kategori Menu</h2>
            <p class="text-sm text-gray-500">Kelola kelompok menu makanan/minuman.</p>
        </div>
        <button onclick="openModal('create')" class="bg-primary hover:bg-[#EA580C] text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition shadow-md shadow-primary/30 flex items-center gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i> Tambah Kategori
        </button>
    </div>

    <div id="data-container" class="bg-surface border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-gray-50/50 text-gray-500">
                    <tr>
                        <th class="px-6 py-4 font-semibold w-16">No</th>
                        <th class="px-6 py-4 font-semibold">Nama Kategori</th>
                        <th class="px-6 py-4 font-semibold text-center">Jumlah Menu</th>
                        <th class="px-6 py-4 font-semibold text-center">Status</th>
                        <th class="px-6 py-4 font-semibold text-center w-28">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($categories as $index => $category)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-6 py-4 text-gray-500">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 font-semibold text-secondary">{{ $category->name }}</td>
                        <td class="px-6 py-4 text-center text-gray-500">
                            <span class="bg-gray-100 px-3 py-1 rounded-full text-xs font-bold">{{ $category->menus_count }} Item</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($category->is_active)
                                <span class="px-3 py-1 text-[10px] uppercase tracking-wider font-bold rounded-full bg-green-50 text-success border border-green-100">Aktif</span>
                            @else
                                <span class="px-3 py-1 text-[10px] uppercase tracking-wider font-bold rounded-full bg-red-50 text-danger border border-red-100">Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="openModal('edit', {{ $category->id }})" class="p-1.5 text-blue-500 bg-blue-50 hover:bg-blue-500 hover:text-white transition rounded-lg"><i data-lucide="edit-2" class="w-4 h-4"></i></button>
                                <button onclick="deleteData({{ $category->id }})" class="p-1.5 text-danger bg-red-50 hover:bg-danger hover:text-white transition rounded-lg"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">Belum ada data kategori.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="kategoriModal" class="fixed inset-0 z-[100] bg-secondary/60 backdrop-blur-sm hidden flex-col items-center justify-center p-4">
    <div class="bg-surface w-full max-w-md rounded-2xl shadow-2xl border border-gray-100 overflow-hidden transform scale-95 opacity-0 transition-all duration-300" id="modalContent">
        <div class="flex justify-between items-center p-5 border-b border-gray-100 bg-gray-50/50">
            <h3 class="text-lg font-bold text-secondary" id="modalTitle">Tambah Kategori</h3>
            <button type="button" onclick="closeModal()" class="text-gray-400 hover:text-danger hover:bg-red-50 p-1.5 rounded-lg transition"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>
        <form id="kategoriForm">
            <div class="p-5 space-y-4">
                <input type="hidden" id="kategori_id" name="id">
                <div>
                    <label class="block text-sm font-semibold text-secondary mb-1.5">Nama Kategori <span class="text-danger">*</span></label>
                    <input type="text" id="name" name="name" required placeholder="Contoh: Makanan Utama" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary transition outline-none text-sm text-secondary bg-gray-50 focus:bg-white">
                    <span class="text-xs text-danger hidden mt-1" id="err_name"></span>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-secondary mb-1.5">Status <span class="text-danger">*</span></label>
                    <select id="is_active" name="is_active" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary transition outline-none text-sm text-secondary bg-gray-50 focus:bg-white">
                        <option value="1">Aktif</option>
                        <option value="0">Nonaktif</option>
                    </select>
                </div>
            </div>
            <div class="p-5 border-t border-gray-100 bg-gray-50/50 flex justify-end gap-3">
                <button type="button" onclick="closeModal()" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-gray-600 bg-white border border-gray-200 hover:bg-gray-50 transition">Batal</button>
                <button type="submit" id="btnSave" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-white bg-primary hover:bg-[#EA580C] transition shadow-md shadow-primary/30 flex items-center gap-2"><i data-lucide="save" class="w-4 h-4"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let isEditMode = false;
    function refreshGrid() { $('#data-container').load(window.location.href + ' #data-container > *', function() { lucide.createIcons(); }); }
    function openModal(type, id = null) {
        isEditMode = (type === 'edit');
        $('#err_name').addClass('hidden').text('');
        $('#kategoriForm')[0].reset();
        if (isEditMode) {
            $('#modalTitle').text('Edit Kategori');
            $('#kategori_id').val(id);
            $.get(`/admin/categories/${id}/edit`, function(data) {
                $('#name').val(data.name);
                $('#is_active').val(data.is_active);
            });
        } else {
            $('#modalTitle').text('Tambah Kategori');
            $('#kategori_id').val('');
        }
        $('#kategoriModal').removeClass('hidden').addClass('flex');
        setTimeout(() => { $('#modalContent').removeClass('scale-95 opacity-0').addClass('scale-100 opacity-100'); }, 10);
    }
    function closeModal() {
        $('#modalContent').removeClass('scale-100 opacity-100').addClass('scale-95 opacity-0');
        setTimeout(() => { $('#kategoriModal').removeClass('flex').addClass('hidden'); }, 300);
    }
    $('#kategoriForm').submit(function(e) {
        e.preventDefault();
        $('#err_name').addClass('hidden');
        let id = $('#kategori_id').val();
        let url = isEditMode ? `/admin/categories/${id}` : `/admin/categories`;
        let btn = $('#btnSave'); let orig = btn.html();
        btn.prop('disabled', true).html('<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i> Menyimpan...'); lucide.createIcons();

        $.ajax({
            url: url, type: isEditMode ? 'PUT' : 'POST', data: $(this).serialize(),
            success: function(res) { closeModal(); Alert.success(res.message); refreshGrid(); },
            error: function(xhr) {
                if(xhr.status === 422) { $('#err_name').removeClass('hidden').text(xhr.responseJSON.errors.name[0]); } 
                else { Alert.error('Terjadi kesalahan.'); }
            },
            complete: function() { btn.prop('disabled', false).html(orig); lucide.createIcons(); }
        });
    });
    function deleteData(id) {
        Alert.confirmDelete(function() {
            $.ajax({ url: `/admin/categories/${id}`, type: 'DELETE',
                success: function(res) { Alert.success(res.message); refreshGrid(); },
                error: function(xhr) { Alert.error(xhr.responseJSON?.message || 'Gagal dihapus.'); }
            });
        });
    }
</script>
@endpush