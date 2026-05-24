@extends('layouts.app')

@section('title', 'Pengaturan Sistem')

@section('content')
<div class="space-y-6 max-w-5xl mx-auto">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-secondary">Pengaturan Sistem</h2>
            <p class="text-sm text-gray-500">Sesuaikan profil restoran, biaya, dan konfigurasi printer.</p>
        </div>
    </div>

    <form id="settingForm" enctype="multipart/form-data">
        <input type="hidden" name="_method" value="PUT">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            {{-- Kolom Kiri: Profil + Biaya --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Profil Restoran --}}
                <div class="bg-surface p-6 rounded-2xl border border-gray-100 shadow-sm">
                    <h3 class="text-lg font-bold text-secondary mb-5 flex items-center gap-2">
                        <i data-lucide="store" class="w-5 h-5 text-primary"></i> Profil Restoran
                    </h3>
                    <div class="space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-semibold text-secondary mb-1.5">Nama Restoran <span class="text-danger">*</span></label>
                                <input type="text" name="resto_name" id="prev_resto_name" value="{{ $setting->resto_name }}" required
                                       oninput="updatePreview()" 
                                       class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary transition outline-none text-sm text-secondary bg-gray-50 focus:bg-white">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-secondary mb-1.5">Nomor Telepon</label>
                                <input type="text" name="phone" id="prev_phone" value="{{ $setting->phone }}" 
                                       oninput="updatePreview()"
                                       class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary transition outline-none text-sm text-secondary bg-gray-50 focus:bg-white">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-secondary mb-1.5">Alamat Lengkap</label>
                            <textarea name="address" id="prev_address" rows="3" oninput="updatePreview()"
                                      class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary transition outline-none text-sm text-secondary bg-gray-50 focus:bg-white">{{ $setting->address }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Biaya Tambahan --}}
                <div class="bg-surface p-6 rounded-2xl border border-gray-100 shadow-sm">
                    <h3 class="text-lg font-bold text-secondary mb-5 flex items-center gap-2">
                        <i data-lucide="percent" class="w-5 h-5 text-primary"></i> Biaya Tambahan
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-secondary mb-1.5">Pajak (PPN) <span class="text-danger">*</span></label>
                            <div class="relative">
                                <input type="number" step="0.01" min="0" max="100" name="tax" value="{{ $setting->tax }}" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary transition outline-none text-sm text-secondary bg-gray-50 focus:bg-white text-right pr-10">
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-gray-500 font-bold">%</div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-secondary mb-1.5">Service Charge <span class="text-danger">*</span></label>
                            <div class="relative">
                                <input type="number" step="0.01" min="0" max="100" name="service_charge" value="{{ $setting->service_charge }}" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary transition outline-none text-sm text-secondary bg-gray-50 focus:bg-white text-right pr-10">
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-gray-500 font-bold">%</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Konfigurasi Printer Thermal --}}
                <div class="bg-surface p-6 rounded-2xl border border-gray-100 shadow-sm">
                    <h3 class="text-lg font-bold text-secondary mb-5 flex items-center gap-2">
                        <i data-lucide="printer" class="w-5 h-5 text-primary"></i> Konfigurasi Printer Thermal
                    </h3>
                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-semibold text-secondary mb-2">Ukuran Kertas <span class="text-danger">*</span></label>
                            <div class="flex gap-3">
                                <label class="flex-1 cursor-pointer">
                                    <input type="radio" name="thermal_printer_width" value="58mm" {{ $setting->thermal_printer_width == '58mm' ? 'checked' : '' }} onchange="updatePreview()" class="sr-only peer">
                                    <div class="peer-checked:border-primary peer-checked:bg-primary/5 peer-checked:text-primary border-2 border-gray-200 rounded-xl p-4 text-center transition">
                                        <i data-lucide="receipt" class="w-6 h-6 mx-auto mb-1"></i>
                                        <p class="font-bold text-sm">58mm</p>
                                        <p class="text-xs text-gray-400 mt-0.5">Printer kecil</p>
                                    </div>
                                </label>
                                <label class="flex-1 cursor-pointer">
                                    <input type="radio" name="thermal_printer_width" value="80mm" {{ ($setting->thermal_printer_width ?? '80mm') == '80mm' ? 'checked' : '' }} onchange="updatePreview()" class="sr-only peer">
                                    <div class="peer-checked:border-primary peer-checked:bg-primary/5 peer-checked:text-primary border-2 border-gray-200 rounded-xl p-4 text-center transition">
                                        <i data-lucide="scroll-text" class="w-6 h-6 mx-auto mb-1"></i>
                                        <p class="font-bold text-sm">80mm</p>
                                        <p class="text-xs text-gray-400 mt-0.5">Printer standar</p>
                                    </div>
                                </label>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-secondary mb-1.5">Teks Footer Struk</label>
                            <textarea name="receipt_footer_text" id="prev_footer" rows="2" oninput="updatePreview()" placeholder="Contoh: Terima kasih atas kunjungan Anda! Sampai jumpa lagi."
                                      class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary transition outline-none text-sm text-secondary bg-gray-50 focus:bg-white">{{ $setting->receipt_footer_text }}</textarea>
                            <p class="text-xs text-gray-400 mt-1">Teks ini akan muncul di bagian bawah setiap struk pembayaran.</p>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Kolom Kanan: Logo + Preview Struk --}}
            <div class="space-y-6">
                
                {{-- Logo --}}
                <div class="bg-surface p-6 rounded-2xl border border-gray-100 shadow-sm">
                    <h3 class="text-lg font-bold text-secondary mb-5 flex items-center gap-2">
                        <i data-lucide="image" class="w-5 h-5 text-primary"></i> Logo Restoran
                    </h3>
                    <div class="flex flex-col items-center justify-center">
                        <div class="w-24 h-24 rounded-2xl bg-gray-50 border-2 border-dashed border-gray-200 flex items-center justify-center overflow-hidden mb-4 relative group" id="logoPreviewWrapper">
                            @if($setting->logo)
                                <img src="{{ Storage::url($setting->logo) }}" alt="Logo" id="logoPreviewImg" class="w-full h-full object-cover">
                            @else
                                <i data-lucide="camera" class="w-8 h-8 text-gray-300" id="logoIcon"></i>
                                <img id="logoPreviewImg" class="w-full h-full object-cover hidden">
                            @endif
                        </div>
                        <input type="file" name="logo" id="logoInput" accept="image/*" onchange="previewLogo(this)"
                               class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
                        <p class="text-[10px] text-gray-400 mt-2 text-center">Format: JPG, PNG. Maksimal 2MB.</p>
                    </div>
                </div>

                {{-- Preview Struk --}}
                <div class="bg-surface p-5 rounded-2xl border border-gray-100 shadow-sm">
                    <h3 class="text-sm font-bold text-secondary mb-3 flex items-center gap-2">
                        <i data-lucide="eye" class="w-4 h-4 text-primary"></i> Preview Struk
                    </h3>
                    <div id="receiptPreview" style="font-family: 'Courier New', monospace; font-size: 11px; background: #fff; border: 1px dashed #ccc; padding: 12px; border-radius: 8px; color: #111;">
                        <div style="text-align:center; margin-bottom:8px;">
                            <div style="font-weight:bold; font-size:13px; text-transform:uppercase;" id="pv_name">{{ $setting->resto_name }}</div>
                            <div style="font-size:10px; color:#555;" id="pv_address">{{ $setting->address }}</div>
                            <div style="font-size:10px; color:#555;" id="pv_phone">{{ $setting->phone ? 'Telp: '.$setting->phone : '' }}</div>
                        </div>
                        <div style="border-top: 1px dashed #000; margin: 6px 0;"></div>
                        <div style="font-size:10px;">
                            <div style="display:flex; justify-content:space-between;"><span>No Order</span><span style="font-weight:bold;">ORD-XXXX</span></div>
                            <div style="display:flex; justify-content:space-between;"><span>Tanggal</span><span>{{ now()->format('d/m/Y H:i') }}</span></div>
                            <div style="display:flex; justify-content:space-between;"><span>Meja</span><span>Meja 1</span></div>
                        </div>
                        <div style="border-top: 1px dashed #000; margin: 6px 0;"></div>
                        <div style="font-size:10px;">
                            <div><strong>Nasi Goreng Spesial</strong></div>
                            <div style="display:flex; justify-content:space-between; padding-left:8px;"><span>1 x 25.000</span><span>25.000</span></div>
                        </div>
                        <div style="border-top: 1px dashed #000; margin: 6px 0;"></div>
                        <div style="font-size:10px;">
                            <div style="display:flex; justify-content:space-between; font-weight:bold; font-size:12px;"><span>TOTAL</span><span>25.000</span></div>
                        </div>
                        <div style="border-top: 1px dashed #000; margin: 6px 0;"></div>
                        <div style="text-align:center; font-size:10px; color:#555;" id="pv_footer">{{ $setting->receipt_footer_text ?? 'Terima kasih!' }}</div>
                    </div>
                </div>

                <button type="submit" id="btnSave" class="w-full bg-primary hover:bg-[#ca8a04] text-white px-5 py-3.5 rounded-xl text-sm font-bold transition shadow-md shadow-primary/30 flex justify-center items-center gap-2">
                    <i data-lucide="save" class="w-5 h-5"></i> Simpan Pengaturan
                </button>

            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    function previewLogo(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById('logoPreviewImg');
                const icon = document.getElementById('logoIcon');
                img.src = e.target.result;
                img.classList.remove('hidden');
                if (icon) icon.classList.add('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function updatePreview() {
        const name    = document.getElementById('prev_resto_name')?.value || '';
        const phone   = document.getElementById('prev_phone')?.value || '';
        const address = document.getElementById('prev_address')?.value || '';
        const footer  = document.getElementById('prev_footer')?.value || 'Terima kasih!';

        document.getElementById('pv_name').textContent    = name;
        document.getElementById('pv_phone').textContent   = phone ? 'Telp: ' + phone : '';
        document.getElementById('pv_address').textContent = address;
        document.getElementById('pv_footer').textContent  = footer;
    }

    $('#settingForm').submit(function(e) {
        e.preventDefault();
        let btn = $('#btnSave');
        let originalText = btn.html();
        let formData = new FormData(this);
        
        btn.prop('disabled', true).html('<i data-lucide="loader-2" class="w-5 h-5 animate-spin"></i> Menyimpan...');
        lucide.createIcons();

        $.ajax({
            url: "{{ route('admin.settings.update') }}",
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                Alert.success(response.message);
                setTimeout(() => { location.reload(); }, 1500);
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    let errorMsg = Object.values(errors).map(err => err.join(', ')).join('<br>');
                    Alert.error(errorMsg || 'Mohon periksa kembali form pengisian Anda.');
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
</script>
@endpush