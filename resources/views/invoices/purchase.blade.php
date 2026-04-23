<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Nota Pembelian Sparepart #{{ $purchase->id }}</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        body {
            margin: 0;
            padding: 24px;
            font-size: 12px;
            color: #111827;
        }

        .invoice {
            max-width: 800px;
            margin: 0 auto;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 24px;
        }

        .flex { display: flex; }
        .justify-between { justify-content: space-between; }
        .items-start { align-items: flex-start; }

        .mb-1 { margin-bottom: 4px; }
        .mb-2 { margin-bottom: 8px; }
        .mb-3 { margin-bottom: 12px; }
        .mb-4 { margin-bottom: 16px; }
        .mb-6 { margin-bottom: 24px; }

        .text-xs { font-size: 10px; }
        .text-sm { font-size: 12px; }
        .text-base { font-size: 14px; }
        .font-bold { font-weight: 700; }
        .font-semibold { font-weight: 600; }
        .uppercase { text-transform: uppercase; }
        .text-right { text-align: right; }

        .text-muted { color: #6b7280; }
        .text-primary { color: #f59e0b; }

        .border { border: 1px solid #e5e7eb; }
        .rounded { border-radius: 6px; }

        .py-1 { padding-top: 4px; padding-bottom: 4px; }
        .py-2 { padding-top: 8px; padding-bottom: 8px; }
        .px-2 { padding-left: 8px; padding-right: 8px; }
        .px-3 { padding-left: 12px; padding-right: 12px; }
        .p-2 { padding: 8px; }
        .p-3 { padding: 12px; }

        .bg-light { background-color: #f9fafb; }

        .divider {
            border-top: 1px solid #e5e7eb;
            margin: 16px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        th, td {
            padding: 6px 8px;
        }

        thead th {
            background-color: #f3f4f6;
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
        }

        tbody tr:nth-child(even) td {
            background-color: #f9fafb;
        }

        tfoot td {
            border-top: 1px solid #e5e7eb;
            font-weight: 600;
        }

        .footer {
            margin-top: 24px;
            font-size: 10px;
            color: #6b7280;
        }
    </style>
</head>
<body>
<div class="invoice">
    {{-- Header: Identitas Toko & Nota --}}
    <div class="flex justify-between items-start mb-4">
        <div>
            <div class="text-base font-bold text-primary mb-1">
                TechFix Service & Sparepart
            </div>
            <div class="text-xs text-muted">
                Jl. Contoh No. 123, Jakarta<br>
                Telp: 021-123456, WA: 08xx-xxxx-xxxx
            </div>
        </div>
        <div class="text-right">
            <div class="text-xs uppercase text-muted">Nota Pembelian Sparepart</div>
            <div class="text-sm font-bold">#{{ sprintf('PC-%05d', $purchase->id) }}</div>
            <div class="text-xs text-muted">
                Tanggal: {{ optional($purchase->purchase_date)->format('d/m/Y') }}
            </div>
        </div>
    </div>

    <div class="divider"></div>

    {{-- Info Customer --}}
    <div class="mb-4">
        <div class="p-2 rounded bg-light" style="width: 50%;">
            <div class="text-xs font-semibold text-muted mb-1">Customer</div>
            <div class="text-sm font-semibold mb-1">
                {{ $customer->name ?? '-' }}
            </div>
            <div class="text-xs text-muted">
                @if(!empty($customer->phone))
                    Telp: {{ $customer->phone }}<br>
                @endif
                @if(!empty($customer->email))
                    Email: {{ $customer->email }}<br>
                @endif
            </div>
        </div>
    </div>

    {{-- Rincian Sparepart --}}
    <div class="mb-3">
        <div class="text-xs font-semibold text-muted mb-1">Rincian Pembelian Sparepart</div>
        <table>
            <thead>
            <tr>
                <th style="width: 40%;">Nama Sparepart</th>
                <th style="width: 15%;">Qty</th>
                <th style="width: 20%;" class="text-right">Harga</th>
                <th style="width: 25%;" class="text-right">Subtotal</th>
            </tr>
            </thead>
            <tbody>
            @forelse($items as $item)
                <tr>
                    <td>
                        {{ $item->sparepart->name ?? 'Sparepart' }}
                    </td>
                    <td>{{ $item->qty }}</td>
                    <td class="text-right">
                        Rp {{ number_format($item->price, 0, ',', '.') }}
                    </td>
                    <td class="text-right">
                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-xs text-muted">
                        Tidak ada item sparepart.
                    </td>
                </tr>
            @endforelse
            </tbody>
            <tfoot>
            <tr>
                <td colspan="3" class="text-right">Total</td>
                <td class="text-right">
                    Rp {{ number_format($total_amount ?? 0, 0, ',', '.') }}
                </td>
            </tr>
            </tfoot>
        </table>
    </div>

    {{-- Info Garansi Sparepart --}}
    <div class="mb-3">
        <div class="text-xs font-semibold text-muted mb-1">Informasi Garansi Sparepart</div>
        <div class="border rounded p-2 text-xs">
            @if($sparepart_warranty_to)
                Garansi sparepart berlaku sampai:
                <strong>{{ optional($sparepart_warranty_to)->format('d/m/Y') }}</strong>.<br>
                Garansi mengikuti ketentuan produsen dan tidak berlaku untuk kerusakan akibat pemasangan atau penggunaan yang tidak sesuai.
            @else
                Tidak ada informasi garansi sparepart.
            @endif
        </div>
    </div>

    {{-- Catatan / Footer --}}
    <div class="footer">
        <div class="mb-1">
            Barang yang sudah dibeli tidak dapat dikembalikan, kecuali sesuai ketentuan garansi.
        </div>
        <div class="mb-1">
            Simpan nota ini sebagai bukti pembelian dan klaim garansi.
        </div>
        <div>
            Terima kasih telah berbelanja di TechFix Service & Sparepart.
        </div>
    </div>
</div>
</body>
</html>