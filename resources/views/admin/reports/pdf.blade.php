<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan - {{ $setting->resto_name ?? 'Restoran' }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #111; background: #fff; }
        
        .header { text-align: center; padding: 20px 0 15px; border-bottom: 2px solid #F97316; margin-bottom: 20px; }
        .header .logo { max-height: 60px; margin-bottom: 10px; }
        .header h1 { font-size: 18px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; color: #000000; }
        .header .subtitle { font-size: 11px; color: #6B7280; margin-top: 4px; }
        .header .info { font-size: 10px; color: #6B7280; margin-top: 3px; }
        .header .periode { font-size: 12px; font-weight: bold; color: #F97316; margin-top: 8px; }

        .stats { display: table; width: 100%; margin-bottom: 20px; }
        .stat-card { display: table-cell; width: 33.33%; padding: 12px 15px; border: 1px solid #E5E7EB; border-radius: 8px; text-align: center; }
        .stat-card + .stat-card { border-left: none; }
        .stat-label { font-size: 10px; color: #6B7280; text-transform: uppercase; font-weight: bold; letter-spacing: 0.5px; }
        .stat-value { font-size: 16px; font-weight: bold; color: #000000; margin-top: 4px; }

        .section-title { font-size: 13px; font-weight: bold; color: #000000; margin-bottom: 10px; padding-bottom: 5px; border-bottom: 1px solid #E5E7EB; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        thead th { background-color: #F9FAFB; color: #374151; font-weight: bold; font-size: 10px; text-transform: uppercase; letter-spacing: 0.5px; padding: 8px 10px; text-align: left; border-bottom: 2px solid #E5E7EB; }
        tbody td { padding: 7px 10px; border-bottom: 1px solid #F3F4F6; font-size: 10px; color: #374151; }
        tbody tr:nth-child(even) td { background-color: #FAFAFA; }

        .badge-rank { display: inline-block; width: 20px; height: 20px; line-height: 20px; text-align: center; border-radius: 50%; background: #F3F4F6; font-weight: bold; font-size: 10px; }
        .badge-rank-1 { background: #F97316; color: white; }

        .footer { margin-top: 30px; padding-top: 15px; border-top: 1px solid #E5E7EB; display: flex; justify-content: space-between; align-items: flex-end; }
        .footer .generated { font-size: 10px; color: #9CA3AF; }
        .footer .signature { text-align: center; font-size: 10px; color: #6B7280; }
        .signature-line { width: 150px; border-top: 1px solid #374151; margin-top: 40px; }

        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .text-primary { color: #F97316; }
    </style>
</head>
<body>

    {{-- Header Laporan --}}
    <div class="header">
        @if($setting && $setting->logo)
            <img src="{{ public_path('storage/' . $setting->logo) }}" class="logo" alt="Logo">
        @endif
        <h1>{{ $setting->resto_name ?? 'Restoran' }}</h1>
        @if($setting && $setting->address)
            <div class="info">{{ $setting->address }}</div>
        @endif
        @if($setting && $setting->phone)
            <div class="info">Telp: {{ $setting->phone }}</div>
        @endif
        <div class="subtitle" style="margin-top: 10px;">LAPORAN PENJUALAN</div>
        <div class="periode">Periode: {{ $startDate->translatedFormat('d F Y') }} &mdash; {{ $endDate->translatedFormat('d F Y') }}</div>
    </div>

    {{-- Statistik Ringkasan --}}
    <table class="stats" style="margin-bottom: 20px;">
        <tr>
            <td style="width: 33%; padding: 12px; border: 1px solid #E5E7EB; border-radius: 6px; text-align: center; background: #FFFBEB;">
                <div style="font-size: 10px; color: #6B7280; text-transform: uppercase; font-weight: bold;">Total Pendapatan</div>
                <div style="font-size: 15px; font-weight: bold; color: #000000; margin-top: 4px;">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
            </td>
            <td style="width: 3%;"></td>
            <td style="width: 30%; padding: 12px; border: 1px solid #E5E7EB; border-radius: 6px; text-align: center; background: #EFF6FF;">
                <div style="font-size: 10px; color: #6B7280; text-transform: uppercase; font-weight: bold;">Total Pesanan</div>
                <div style="font-size: 15px; font-weight: bold; color: #000000; margin-top: 4px;">{{ number_format($totalOrders, 0, ',', '.') }} Order</div>
            </td>
            <td style="width: 3%;"></td>
            <td style="width: 30%; padding: 12px; border: 1px solid #E5E7EB; border-radius: 6px; text-align: center; background: #FFF7ED;">
                <div style="font-size: 10px; color: #6B7280; text-transform: uppercase; font-weight: bold;">Rata-rata per Order</div>
                <div style="font-size: 15px; font-weight: bold; color: #000000; margin-top: 4px;">Rp {{ number_format($averageOrderValue, 0, ',', '.') }}</div>
            </td>
        </tr>
    </table>

    {{-- Top 5 Menu Terlaris --}}
    <div class="section-title">⭐ Top 5 Menu Terlaris</div>
    <table>
        <thead>
            <tr>
                <th style="width: 40px;">#</th>
                <th>Nama Menu</th>
                <th class="text-right">Qty Terjual</th>
                <th class="text-right">Total Revenue</th>
            </tr>
        </thead>
        <tbody>
            @forelse($topMenus as $i => $item)
            <tr>
                <td class="text-center">
                    <span class="badge-rank {{ $i == 0 ? 'badge-rank-1' : '' }}">{{ $i + 1 }}</span>
                </td>
                <td class="font-bold">{{ $item->menu->name ?? 'Menu Dihapus' }}</td>
                <td class="text-right">{{ $item->total_sold }} porsi</td>
                <td class="text-right font-bold">Rp {{ number_format($item->total_revenue, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center" style="color: #9CA3AF;">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Rincian Transaksi --}}
    <div class="section-title">📋 Rincian Transaksi</div>
    <table>
        <thead>
            <tr>
                <th style="width: 30px;">No</th>
                <th>Waktu</th>
                <th>No. Order</th>
                <th class="text-center">Meja</th>
                <th>Metode</th>
                <th>Kasir</th>
                <th class="text-right">Nominal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $i => $order)
            <tr>
                <td class="text-center text-center">{{ $i + 1 }}</td>
                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                <td class="font-bold">{{ $order->order_number }}</td>
                <td class="text-center">{{ $order->table->table_number }}</td>
                <td style="text-transform: uppercase;">{{ $order->payment->payment_method ?? '-' }}</td>
                <td>{{ $order->payment->user->name ?? 'System' }}</td>
                <td class="text-right font-bold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center" style="color: #9CA3AF;">Tidak ada transaksi pada periode ini.</td>
            </tr>
            @endforelse
        </tbody>
        @if($orders->count() > 0)
        <tfoot>
            <tr>
                <td colspan="6" class="font-bold text-right" style="padding: 8px 10px; border-top: 2px solid #000000; background: #F9FAFB;">TOTAL KESELURUHAN</td>
                <td class="font-bold text-right" style="padding: 8px 10px; border-top: 2px solid #000000; background: #F9FAFB; color: #F97316; font-size: 12px;">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
        @endif
    </table>

    {{-- Footer --}}
    <div class="footer">
        <div class="generated">
            Dicetak pada: {{ now()->translatedFormat('d F Y, H:i') }} WIB
        </div>
        <div class="signature">
            <p>Mengetahui,</p>
            <div class="signature-line"></div>
            <p>Admin / Manajer</p>
        </div>
    </div>

</body>
</html>
