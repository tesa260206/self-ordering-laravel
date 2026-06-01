@extends('layouts.app')

@section('title', 'Pengguna Sistem')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-secondary">Pengguna Sistem</h2>
            <p class="text-sm text-gray-500">Kelola akun admin, kasir, dan bagian dapur (kitchen).</p>
        </div>
        <button onclick="openModal('create')" class="bg-primary hover:bg-[#EA580C] text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition shadow-md shadow-primary/30 flex items-center gap-2">
            <i data-lucide="user-plus" class="w-4 h-4"></i>
            Tambah Pengguna
        </button>
    </div>

    <div id="data-container" class="bg-surface border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-gray-50/50 text-gray-500">
                    <tr>
                        <th class="px-6 py-4 font-semibold w-16">No</th>
                        <th class="px-6 py-4 font-semibold">Nama Pengguna</th>
                        <th class="px-6 py-4 font-semibold text-center">Role / Akses</th>
                        <th class="px-6 py-4 font-semibold text-center">Status</th>
                        <th class="px-6 py-4 font-semibold text-center w-28">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $index => $user)
                    <tr class="hover:bg-gray-50/50 transition group">
                        <td class="px-6 py-4 text-gray-500">{{ $index + 1 }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=EAB308&color=fff" class="w-10 h-10 rounded-full border border-gray-200" alt="Avatar">
                                <div>
                                    <p class="font-bold text-secondary">{{ $user->name }}</p>
                                    <p class="text-xs text-gray-400">{{ '@' . $user->username }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @php
                                $roleColors = [
                                    'admin' => 'bg-purple-50 text-purple-600 border-purple-100',
                                    'cashier' => 'bg-blue-50 text-blue-600 border-blue-100',
                                    'kitchen' => 'bg-orange-50 text-orange-600 border-orange-100'
                                ];
                                $roleName = $user->getRoleNames()->first() ?? 'Tidak ada';
                            @endphp
                            <span class="px-3 py-1.5 text-[10px] uppercase tracking-wider font-bold rounded-full border {{ $roleColors[$roleName] ?? 'bg-gray-100 text-gray-600' }}">
                                {{ $roleName }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($user->is_active)
                                <span class="px-3 py-1 text-[10px] uppercase tracking-wider font-bold rounded-full bg-green-50 text-success border border-green-100">Aktif</span>
                            @else
                                <span class="px-3 py-1 text-[10px] uppercase tracking-wider font-bold rounded-full bg-red-50 text-danger border border-red-100">Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2 transition-opacity">
                                <button onclick="openModal('edit', {{ $user->id }})" class="p-1.5 text-blue-500 bg-blue-50 hover:bg-blue-500 hover:text-white transition rounded-lg" title="Edit">
                                    <i data-lucide="edit-2" class="w-4 h-4"></i>
                                </button>
                                @if($user->id !== auth()->id())
                                <button onclick="deleteData({{ $user->id }})" class="p-1.5 text-danger bg-red-50 hover:bg-danger hover:text-white transition rounded-lg" title="Hapus">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">Belum ada data pengguna.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="userModal" class="fixed inset-0 z-[100] bg-secondary/60 backdrop-blur-sm hidden flex-col items-center justify-center p-4">
    <div class="bg-surface w-full max-w-lg rounded-2xl shadow-2xl border border-gray-100 overflow-hidden transform scale-95 opacity-0 transition-all duration-300 flex flex-col" id="modalContent">
        <div class="flex justify-between items-center p-5 border-b border-gray-100 bg-gray-50/50">
            <h3 class="text-lg font-bold text-secondary flex items-center gap-2">
                <i data-lucide="users" class="w-5 h-5 text-primary"></i>
                <span id="modalTitle">Tambah Pengguna</span>
            </h3>
            <button type="button" onclick="closeModal()" class="text-gray-400 hover:text-danger hover:bg-red-50 p-1.5 rounded-lg transition"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>

        <form id="userForm">
            <div class="p-5 space-y-4">
                <input type="hidden" id="user_id" name="id">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-secondary mb-1.5">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" id="name" name="name" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary transition outline-none text-sm text-secondary bg-gray-50 focus:bg-white">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-secondary mb-1.5">Username <span class="text-danger">*</span></label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">@</span>
                            <input type="text" id="username" name="username" required class="w-full pl-8 pr-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary transition outline-none text-sm text-secondary bg-gray-50 focus:bg-white">
                        </div>
                        <span class="text-xs text-danger hidden mt-1 block" id="err_username"></span>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-secondary mb-1.5">Password <span class="text-xs text-gray-400 font-normal" id="password_hint"></span></label>
                    <input type="password" id="password" name="password" placeholder="Minimal 6 karakter" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary transition outline-none text-sm text-secondary bg-gray-50 focus:bg-white">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-secondary mb-1.5">Role / Akses <span class="text-danger">*</span></label>
                        <select id="role" name="role" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary transition outline-none text-sm text-secondary bg-gray-50 focus:bg-white">
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-secondary mb-1.5">Status Akun <span class="text-danger">*</span></label>
                        <select id="is_active" name="is_active" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary transition outline-none text-sm text-secondary bg-gray-50 focus:bg-white">
                            <option value="1">Aktif (Bisa Login)</option>
                            <option value="0">Nonaktif (Diblokir)</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="p-5 border-t border-gray-100 bg-gray-50/50 flex justify-end gap-3">
                <button type="button" onclick="closeModal()" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-gray-600 bg-white border border-gray-200 hover:bg-gray-100 transition">Batal</button>
                <button type="submit" id="btnSave" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-white bg-primary hover:bg-[#EA580C] transition shadow-md shadow-primary/30 flex items-center gap-2">
                    <i data-lucide="save" class="w-4 h-4"></i> Simpan
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
        $('#err_username').addClass('hidden').text('');
        $('#userForm')[0].reset();
        
        if (isEditMode) {
            $('#modalTitle').text('Edit Pengguna');
            $('#user_id').val(id);
            $('#password').removeAttr('required');
            $('#password_hint').text('(Kosongkan jika tidak ingin ganti password)');
            
            $.get(`/admin/users/${id}/edit`, function(data) {
                $('#name').val(data.name);
                $('#username').val(data.username);
                $('#is_active').val(data.is_active);
                $('#role').val(data.role_name); // Set Role
            });
        } else {
            $('#modalTitle').text('Tambah Pengguna');
            $('#user_id').val('');
            $('#password').attr('required', 'required');
            $('#password_hint').text('');
        }

        $('#userModal').removeClass('hidden').addClass('flex');
        setTimeout(() => { $('#modalContent').removeClass('scale-95 opacity-0').addClass('scale-100 opacity-100'); }, 10);
    }

    function closeModal() {
        $('#modalContent').removeClass('scale-100 opacity-100').addClass('scale-95 opacity-0');
        setTimeout(() => { $('#userModal').removeClass('flex').addClass('hidden'); }, 300);
    }

    $('#userForm').submit(function(e) {
        e.preventDefault();
        $('#err_username').addClass('hidden').text('');
        
        let id = $('#user_id').val();
        let url = isEditMode ? `/admin/users/${id}` : `/admin/users`;
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
                    if(errors.username) {
                        $('#err_username').removeClass('hidden').text(errors.username[0]);
                    }
                } else {
                    Alert.error('Terjadi kesalahan sistem.');
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
                url: `/admin/users/${id}`,
                type: 'DELETE',
                success: function(response) {
                    Alert.success(response.message);
                    refreshGrid();
                },
                error: function(xhr) {
                    Alert.error(xhr.responseJSON?.message || 'Gagal menghapus pengguna.');
                }
            });
        });
    }
</script>
@endpush