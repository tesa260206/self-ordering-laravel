<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Table;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage; // Gunakan Storage agar support symlink hosting

class TableController extends Controller
{
    public function index()
    {
        $tables = Table::orderBy('table_number', 'asc')->get();
        return view('admin.tables.index', compact('tables'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'table_number' => 'required|string|unique:tables,table_number|max:10',
            'status' => 'required|in:available,occupied'
        ]);

        // 1. Simpan data meja terlebih dahulu
        $table = Table::create([
            'table_number' => $request->table_number,
            'status' => $request->status,
        ]);

        // 2. Generate URL untuk scan pelanggan (Menggunakan penamaan route agar dinamis)
        $scanUrl = route('customer.welcome', ['table' => $table->table_number]);

        // 3. Nama file QR Code yang akan disimpan
        $fileName = 'qr_meja_' . strtolower(str_replace(' ', '_', $table->table_number)) . '.svg';

        // 4. Generate format SVG ke dalam memory
        $qrContent = QrCode::format('svg')->margin(2)->size(300)->generate($scanUrl);

        // 5. Simpan ke Storage Publik (Otomatis tembus ke public_html/storage/qrcodes via symlink)
        Storage::disk('public')->put('qrcodes/' . $fileName, $qrContent);

        // 6. Update path QR Code ke database agar gampang dibaca oleh fungsi asset() di view
        $table->update([
            'qr_code_path' => 'storage/qrcodes/' . $fileName
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Meja & QR Code berhasil di-generate!'
        ]);
    }

    public function edit(Table $table)
    {
        return response()->json($table);
    }

    public function update(Request $request, Table $table)
    {
        $request->validate([
            'table_number' => 'required|string|max:10|unique:tables,table_number,' . $table->id,
            'status' => 'required|in:available,occupied'
        ]);

        // Simpan nomor meja lama sebelum diupdate untuk keperluan hapus file QR lama
        $oldTableNumber = $table->table_number;

        // Update status dan nomor meja
        $table->update([
            'table_number' => $request->table_number,
            'status' => $request->status,
        ]);

        // Re-generate QR Code HANYA jika nomor meja berubah (agar URL-nya ikut update)
        if ($table->wasChanged('table_number')) {
            
            // A. Hapus file QR lama dari storage (Biar hosting tidak penuh)
            $oldFileName = 'qr_meja_' . strtolower(str_replace(' ', '_', $oldTableNumber)) . '.svg';
            if (Storage::disk('public')->exists('qrcodes/' . $oldFileName)) {
                Storage::disk('public')->delete('qrcodes/' . $oldFileName);
            }

            // B. Buat & Simpan QR Baru
            $scanUrl = route('customer.welcome', ['table' => $table->table_number]);
            $fileName = 'qr_meja_' . strtolower(str_replace(' ', '_', $table->table_number)) . '.svg';
            
            $qrContent = QrCode::format('svg')->margin(2)->size(300)->generate($scanUrl);
            Storage::disk('public')->put('qrcodes/' . $fileName, $qrContent);
            
            // C. Update path ke database
            $table->update(['qr_code_path' => 'storage/qrcodes/' . $fileName]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data meja berhasil diperbarui!'
        ]);
    }

    public function destroy(Table $table)
    {
        if ($table->status === 'occupied') {
            return response()->json(['success' => false, 'message' => 'Meja yang sedang terisi tidak dapat dihapus!'], 400);
        }

        // Cek apakah meja memiliki riwayat pesanan untuk mencegah error foreign key
        if (\App\Models\Order::where('table_id', $table->id)->exists()) {
            return response()->json(['success' => false, 'message' => 'Gagal! Meja ini sudah memiliki riwayat pesanan sehingga tidak bisa dihapus.'], 400);
        }

        // Hapus file fisik QR Code dari Storage dengan aman
        $fileName = 'qr_meja_' . strtolower(str_replace(' ', '_', $table->table_number)) . '.svg';
        if (Storage::disk('public')->exists('qrcodes/' . $fileName)) {
            Storage::disk('public')->delete('qrcodes/' . $fileName);
        }

        $table->delete();

        return response()->json([
            'success' => true,
            'message' => 'Meja berhasil dihapus!'
        ]);
    }
}