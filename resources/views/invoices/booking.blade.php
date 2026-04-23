<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Nota Servis #{{ $booking->id }}</title>
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

        .flex {
            display: flex;
        }

        .justify-between {
            justify-content: space-between;
        }

        .items-start {
            align-items: flex-start;
        }

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

        .border {
            border: 1px solid #e5e7eb;
        }

        .rounded { border-radius: 6px; }

        .py-1 { padding-top: 4px; padding-bottom: 4px; }
        .py-2 { padding-top: 8px; padding-bottom: 8px; }
        .px-2 { padding-left: 8px; padding-right: 8px; }
        .px-3 { padding-left: 12px; padding-right: 12px; }
        .p-2 { padding: 8px; }
        .p-3 { padding: 12px; }

        .bg-light {
            background-color: #f9fafb;
        }

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

        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 999px;
            font-size: 10px;
        }

        .badge-success {
            background-color: #dcfce7;
            color: #166534;
        }

        .badge-warning {
            background-color: #fef3c7;
            color: #92400e;
        }

        .badge-default {
            background-color: #e5e7eb;
            color: #374151;
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
                Jl. Bhakti Suci No.100, Cimpaeun, Kec. Tapos, Kota Depok, Jawa Barat 16459<br>
                Telp: 021-87907233, WA: 0851 7060 4157
            </div>
        </div>
        <div class="text-right">
            <div class="text-xs uppercase text-muted">Nota Servis</div>
            <div class="text-sm font-bold">#{{ sprintf('BK-%05d', $booking->id) }}</div>
            <div class="text-xs text-muted">
                Tanggal: {{ optional($booking->booking_date)->format('d/m/Y') }}<br>
                Selesai: {{ optional($booking->finished_at)->format('d/m/Y H:i') ?? '-' }}
            </div>
        </div>
    </div>

    <div class="divider"></div>

    {{-- Info Customer & Perangkat --}}
    <div class="flex justify-between items-start mb-4">
        <div class="p-2 rounded bg-light" style="width: 49%;">
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
        <div class="p-2 rounded bg-light" style="width: 49%;">
            <div class="text-xs font-semibold text-muted mb-1">Perangkat</div>
            <div class="text-sm font-semibold mb-1">
                {{ $booking->device_type ?? '-' }}
            </div>
            <div class="text-xs text-muted">
                Merek: {{ $booking->device_brand ?? '-' }}<br>
                Model: {{ $booking->device_model ?? '-' }}
            </div>
        </div>
    </div>

    {{-- Deskripsi Problem --}}
    <div class="mb-4">
        <div class="text-xs font-semibold text-muted mb-1">Keluhan / Problem</div>
        <div class="border rounded p-2 text-xs">
            {{ $booking->problem_description ?: '-' }}
        </div>
    </div>

    {{-- Status Servis & Garansi --}}
    <div class="flex justify-between items-start mb-4">
        <div>
            <div class="text-xs font-semibold text-muted mb-1">Status Servis</div>
            @php
                $statusLabel = match($booking->status) {
                    'waiting_queue'     => 'Menunggu Antrean',
                    'checking'          => 'Dicek',
                    'waiting_sparepart' => 'Menunggu Sparepart',
                    'processing'        => 'Dikerjakan',
                    'finished'          => 'Selesai',
                    'cancelled'         => 'Dibatalkan',
                    default             => $booking->status,
                };

                $statusClass = match($booking->status) {
                    'finished'          => 'badge-success',
                    'waiting_sparepart' => 'badge-warning',
                    default             => 'badge-default',
                };
            @endphp
            <span class="badge {{ $statusClass }}">{{ $statusLabel }}</span>
        </div>

        <div class="text-right">
            <div class="text-xs font-semibold text-muted mb-1">Garansi Servis</div>
            <div class="text-xs">
                @if($service_warranty)
                    Berlaku sampai: {{ optional($service_warranty)->format('d/m/Y') }}
                @else
                    -
                @endif
            </div>
        </div>
    </div>

    {{-- Rincian Tagihan: Jasa + Sparepart --}}
    <div class="mb-3">
        <div class="text-xs font-semibold text-muted mb-1">Rincian Tagihan</div>
        <table>
            <thead>
            <tr>
                <th style="width: 40%;">Deskripsi</th>
                <th style="width: 15%;">Qty</th>
                <th style="width: 20%;" class="text-right">Harga</th>
                <th style="width: 25%;" class="text-right">Subtotal</th>
            </tr>
            </thead>
            <tbody>
            {{-- Jasa Servis --}}
            <tr>
                <td>Jasa Servis</td>
                <td>1</td>
                <td class="text-right">
                    Rp {{ number_format($service_fee ?? 0, 0, ',', '.') }}
                </td>
                <td class="text-right">
                    Rp {{ number_format($service_fee ?? 0, 0, ',', '.') }}
                </td>
            </tr>

            {{-- Sparepart --}}
            @forelse($spareparts as $item)
                <tr>
                    <td>
                        {{ $item->sparepart->name ?? 'Sparepart' }}
                    </td>
                    <td>
                        {{ $item->qty }}
                    </td>
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
                        Tidak ada sparepart yang diganti.
                    </td>
                </tr>
            @endforelse
            </tbody>
            <tfoot>
            <tr>
                <td colspan="3" class="text-right">Total Sparepart</td>
                <td class="text-right">
                    Rp {{ number_format($total_spareparts ?? 0, 0, ',', '.') }}
                </td>
            </tr>
            <tr>
                <td colspan="3" class="text-right">Biaya Jasa Servis</td>
                <td class="text-right">
                    Rp {{ number_format($service_fee ?? 0, 0, ',', '.') }}
                </td>
            </tr>
            <tr>
                <td colspan="3" class="text-right">Grand Total</td>
                <td class="text-right">
                    Rp {{ number_format($grand_total ?? 0, 0, ',', '.') }}
                </td>
            </tr>
            </tfoot>
        </table>
    </div>

    {{-- Catatan / Footer --}}
    <div class="footer">
        <div class="mb-2">
            Catatan:
            @if($booking->notes)
                {{ $booking->notes }}
            @else
                -
            @endif
        </div>
        <div class="mb-1">
            * Garansi servis hanya berlaku untuk keluhan yang sama dan tidak mencakup kerusakan baru akibat penggunaan.
        </div>
        <div>
            Terima kasih telah menggunakan layanan kami.
        </div>
    </div>
</div>
</body>
</html>