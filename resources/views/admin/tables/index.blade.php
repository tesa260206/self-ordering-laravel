@extends('layouts.app')

@section('title', 'Manajemen Meja')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-6 rounded-[20px] border border-gray-200 shadow-sm">
        <div>
            <h2 class="text-2xl font-extrabold text-secondary tracking-tight">Data Meja</h2>
            <p class="text-sm text-gray-500 mt-1">Kelola master data meja dan akses QR Code pelanggan.</p>
        </div>
        <button onclick="openModal('create')" class="bg-primary hover:bg-[#ca8a04] text-white px-6 py-3 rounded-xl text-sm font-bold transition-all shadow-md shadow-primary/20 flex items-center gap-2 active:scale-95">
            <i data-lucide="plus" class="w-5 h-5"></i>
            Tambah Meja Baru
        </button>
    </div>

    <div id="table-grid-container">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($tables as $table)
                <div class="bg-white rounded-[20px] border border-gray-200 p-5 flex flex-col justify-between h-full shadow-sm hover:shadow-md transition-shadow">
                    
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-xl font-black shrink-0 {{ $table->status == 'available' ? 'bg-green-50 text-success border border-green-100' : 'bg-orange-50 text-warning border border-orange-100' }}">
                            {{ $table->table_number }}
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-bold text-secondary mb-1">Meja {{ $table->table_number }}</h3>
                            @if($table->status == 'available')
                                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-success"></span> KOSONG
                                </p>
                            @else
                                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-warning animate-pulse"></span> TERISI
                                </p>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center justify-between mt-auto pt-2">
                        
                        <div class="px-3 py-1.5 rounded-lg bg-gray-50 text-xs font-bold text-gray-500 border border-gray-100 flex items-center gap-1.5">
                            @if($table->qr_code_path)
                                <i data-lucide="check-circle-2" class="w-3.5 h-3.5 text-success"></i> QR Ready
                            @else
                                <i data-lucide="x-circle" class="w-3.5 h-3.5 text-danger"></i> No QR
                            @endif
                        </div>

                        <div class="flex gap-2">
                            @if($table->qr_code_path)
                            <button onclick="showQr('{{ asset($table->qr_code_path) }}', '{{ $table->table_number }}')" class="w-9 h-9 rounded-xl bg-gray-50 text-gray-600 hover:bg-primary hover:text-white flex items-center justify-center transition border border-gray-100 active:scale-95" title="Scan Barcode">
                                <i data-lucide="qr-code" class="w-4 h-4"></i>
                            </button>
                            @endif
                            <button onclick="openModal('edit', {{ $table->id }})" class="w-9 h-9 rounded-xl bg-gray-50 text-gray-600 hover:bg-blue-500 hover:text-white flex items-center justify-center transition border border-gray-100 active:scale-95" title="Edit">
                                <i data-lucide="edit-2" class="w-4 h-4"></i>
                            </button>
                            <button onclick="deleteData({{ $table->id }})" class="w-9 h-9 rounded-xl bg-gray-50 text-gray-600 hover:bg-danger hover:text-white flex items-center justify-center transition border border-gray-100 active:scale-95" title="Hapus">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 flex flex-col items-center justify-center bg-white rounded-[20px] border border-gray-200 border-dashed shadow-sm">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4 border border-gray-100">
                        <i data-lucide="layout-grid" class="w-10 h-10 text-gray-300"></i>
                    </div>
                    <p class="text-secondary font-bold text-xl mb-1">Belum Ada Data Meja</p>
                    <p class="text-sm text-gray-500 mb-6">Sistem belum memiliki data meja untuk scan QR pelanggan.</p>
                    <button onclick="openModal('create')" class="text-primary hover:text-[#ca8a04] font-bold text-sm flex items-center gap-1.5 transition">
                        <i data-lucide="plus" class="w-4 h-4"></i> Tambah Meja Sekarang
                    </button>
                </div>
            @endforelse
        </div>
    </div>
</div>

<div id="tableModal" class="fixed inset-0 z-[100] bg-secondary/60 backdrop-blur-sm hidden flex-col items-center justify-center p-4">
    <div class="bg-white w-full max-w-md rounded-[20px] shadow-2xl border border-gray-100 overflow-hidden transform scale-95 opacity-0 transition-all duration-300" id="modalContent">
        
        <div class="flex justify-between items-center p-6 border-b border-gray-100">
            <h3 class="text-xl font-bold text-secondary flex items-center gap-3">
                <div class="w-10 h-10 bg-primary/10 rounded-xl flex items-center justify-center text-primary">
                    <i data-lucide="layout-grid" class="w-5 h-5"></i>
                </div>
                <span id="modalTitle">Tambah Meja</span>
            </h3>
            <button type="button" onclick="closeModal()" class="text-gray-400 hover:text-danger hover:bg-red-50 p-2 rounded-xl transition">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form id="tableForm" class="bg-gray-50/50">
            <div class="p-6 space-y-6">
                <input type="hidden" id="table_id" name="id">
                
                <div>
                    <label class="block text-sm font-bold text-secondary mb-2">Nomor / Nama Meja <span class="text-danger">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i data-lucide="hash" class="w-5 h-5 text-gray-400"></i>
                        </div>
                        <input type="text" id="table_number" name="table_number" required placeholder="Contoh: 01, VIP-A" class="w-full pl-11 pr-4 py-3.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary transition outline-none text-sm text-secondary placeholder-gray-400 bg-white shadow-sm">
                    </div>
                    <span class="text-xs text-danger hidden mt-1 block font-medium" id="err_table_number"></span>
                </div>

                <div>
                    <label class="block text-sm font-bold text-secondary mb-2">Status Meja <span class="text-danger">*</span></label>
                    <div class="relative">
                        <select id="status" name="status" class="w-full pl-4 pr-10 py-3.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary transition outline-none text-sm text-secondary bg-white shadow-sm appearance-none cursor-pointer font-medium">
                            <option value="available">🟢 Kosong (Tersedia)</option>
                            <option value="occupied">🟠 Terisi (Digunakan)</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                            <i data-lucide="chevron-down" class="w-5 h-5 text-gray-400"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6 border-t border-gray-100 bg-white flex justify-end gap-3">
                <button type="button" onclick="closeModal()" class="px-6 py-3 rounded-xl text-sm font-bold text-gray-500 bg-gray-50 border border-gray-200 hover:bg-gray-100 hover:text-secondary transition active:scale-95">Batal</button>
                <button type="submit" id="btnSave" class="px-6 py-3 rounded-xl text-sm font-bold text-white bg-primary hover:bg-[#ca8a04] transition shadow-md shadow-primary/20 flex items-center gap-2 active:scale-95">
                    <i data-lucide="save" class="w-4 h-4"></i> Simpan Data
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
        $('#table-grid-container').load(window.location.href + ' #table-grid-container > *', function() {
            lucide.createIcons();
        });
    }

    function openModal(type, id = null) {
        isEditMode = (type === 'edit');
        $('#err_table_number').addClass('hidden').text('');
        $('#tableForm')[0].reset();
        
        if (isEditMode) {
            $('#modalTitle').text('Edit Data Meja');
            $('#table_id').val(id);
            $('#table_number').val('Memuat...').prop('disabled', true);
            
            $.get(`/admin/tables/${id}/edit`, function(data) {
                $('#table_number').val(data.table_number).prop('disabled', false);
                $('#status').val(data.status);
            }).fail(function() {
                Alert.error('Gagal mengambil data meja.');
                closeModal();
            });
        } else {
            $('#modalTitle').text('Tambah Meja Baru');
            $('#table_id').val('');
            $('#table_number').prop('disabled', false);
        }

        $('#tableModal').removeClass('hidden').addClass('flex');
        setTimeout(() => {
            $('#modalContent').removeClass('scale-95 opacity-0').addClass('scale-100 opacity-100');
        }, 10);
    }

    function closeModal() {
        $('#modalContent').removeClass('scale-100 opacity-100').addClass('scale-95 opacity-0');
        setTimeout(() => {
            $('#tableModal').removeClass('flex').addClass('hidden');
        }, 300);
    }

    $('#tableForm').submit(function(e) {
        e.preventDefault();
        $('#err_table_number').addClass('hidden').text('');

        let id = $('#table_id').val();
        let url = isEditMode ? `/admin/tables/${id}` : `/admin/tables`;
        let method = isEditMode ? 'PUT' : 'POST';
        
        let btn = $('#btnSave');
        let originalText = btn.html();
        btn.prop('disabled', true).html('<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i> Memproses...');
        lucide.createIcons();

        $.ajax({
            url: url,
            type: method,
            data: $(this).serialize(),
            success: function(response) {
                closeModal();
                Alert.success(response.message);
                refreshGrid();
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    if(errors.table_number) {
                        $('#err_table_number').removeClass('hidden').text(errors.table_number[0]);
                        $('#table_number').addClass('border-danger focus:ring-danger/20 focus:border-danger');
                        setTimeout(() => {
                            $('#table_number').removeClass('border-danger focus:ring-danger/20 focus:border-danger');
                        }, 3000);
                    }
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

    function deleteData(id) {
        Alert.confirmDelete(function() {
            $.ajax({
                url: `/admin/tables/${id}`,
                type: 'DELETE',
                success: function(response) {
                    Alert.success(response.message);
                    refreshGrid();
                },
                error: function(xhr) {
                    Alert.error(xhr.responseJSON?.message || 'Gagal menghapus meja.');
                }
            });
        });
    }

    function showQr(qrPath, tableNumber) {
        Swal.fire({
            title: '<span class="text-secondary font-extrabold text-2xl">QR Meja ' + tableNumber + '</span>',
            html: `
                <div class="flex flex-col items-center justify-center p-2">
                    <p class="text-sm text-gray-500 mb-6">Pelanggan dapat memindai QR Code ini untuk mulai memesan.</p>
                    <div class="p-6 bg-white border-2 border-dashed border-gray-200 rounded-[20px] mb-6 relative group">
                        <img src="${qrPath}" alt="QR Code Meja ${tableNumber}" class="w-60 h-60 object-contain relative z-10">
                    </div>
                    <a href="${qrPath}" download="QR_Meja_${tableNumber}.svg" class="inline-flex items-center justify-center gap-2 w-full bg-primary hover:bg-[#ca8a04] text-white px-6 py-4 rounded-xl text-sm font-bold transition shadow-md shadow-primary/20 active:scale-95">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                        Download QR Code
                    </a>
                </div>
            `,
            showConfirmButton: false,
            showCloseButton: true,
            customClass: {
                popup: 'rounded-[24px] border border-gray-100 shadow-2xl p-4',
                closeButton: 'focus:outline-none hover:text-danger hover:bg-red-50 rounded-xl transition m-2'
            }
        });
    }
</script>
@endpush