<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>TechFix Service & Sparepart</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="{{ asset('bootstrap/bootstraps/css/bootstrap.min.css') }}">

    <style>
        body {
            min-height: 100vh;
            background: radial-gradient(circle at top, #1f2937 0, #020617 55%);
            color: #f9fafb;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        .bg-card {
            background-color: rgba(15, 23, 42, 0.9);
            border: 1px solid rgba(148, 163, 184, 0.2);
            border-radius: 1rem;
        }

        .brand-badge {
            width: 36px;
            height: 36px;
            border-radius: 0.75rem;
            background: #f59e0b;
            color: #020617;
            font-weight: 700;
            font-size: 0.75rem;
        }

        .btn-amber {
            background-color: #f59e0b;
            border-color: #f59e0b;
            color: #020617;
        }

        .btn-amber:hover {
            background-color: #fbbf24;
            border-color: #fbbf24;
            color: #020617;
        }

        .border-muted {
            border-color: rgba(148, 163, 184, 0.3) !important;
        }

        .text-muted-soft {
            color: #9ca3af;
        }

        .badge-soft {
            background-color: rgba(55, 65, 81, 0.7);
            color: #e5e7eb;
            border-radius: 999px;
            font-size: 0.65rem;
            padding: 0.15rem 0.5rem;
        }
    </style>
</head>
<body>
<div class="d-flex flex-column min-vh-100 position-relative">
    {{-- Overlay gradient ringan --}}
    <div class="position-absolute top-0 start-0 w-100 h-100" style="pointer-events:none;
        background: radial-gradient(circle at 0% 0%, rgba(245,158,11,0.15), transparent 45%);">
    </div>

    <div class="flex-grow-1 d-flex flex-column position-relative">
        {{-- Navbar --}}
        <header class="py-3">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <div class="brand-badge d-flex justify-content-center align-items-center">
                            TF
                        </div>
                        <div>
                            <div class="fw-semibold" style="font-size: 0.9rem;">TechFix</div>
                            <div class="text-muted-soft" style="font-size: 0.75rem;">Service & Sparepart</div>
                        </div>
                    </div>
                    <span class="text-muted-soft" style="font-size: 0.75rem;">
                        Sistem Informasi Bengkel Komputer
                    </span>
                </div>
            </div>
        </header>

        {{-- Hero --}}
        <main class="flex-grow-1 d-flex align-items-center py-4">
            <div class="container">
                <div class="row align-items-center g-4">
                    {{-- Left: Text --}}
                    <div class="col-md-6">
                        <div class="text-uppercase text-warning fw-semibold mb-2" style="font-size: 0.7rem; letter-spacing: .15em;">
                            TechFix Service & Sparepart
                        </div>
                        <h1 class="fw-bold mb-3" style="font-size: 1.9rem; line-height: 1.3;">
                            Panel Admin & Mekanik<br>
                            untuk Manajemen Servis yang Rapi
                        </h1>
                        <p class="text-muted-soft mb-4" style="font-size: 0.9rem; max-width: 380px;">
                            Kelola antrian servis, pemakaian sparepart, stok, dan laba rugi
                            dalam satu sistem terpadu yang sederhana namun lengkap.
                        </p>

                        <div class="d-flex flex-column flex-sm-row gap-2 mb-3">
                            <a href="{{ url('/admin/login') }}" class="btn btn-amber btn-sm px-3">
                                Login Admin
                            </a>
                            <a href="{{ url('/mechanic/login') }}" class="btn btn-outline-light btn-sm px-3 border-muted">
                                Login Mechanic
                            </a>
                        </div>

                        <div class="text-muted-soft" style="font-size: 0.75rem;">
                            Role yang berbeda akan diarahkan ke panel yang berbeda
                            sesuai hak akses (Admin atau Mechanic).
                        </div>
                    </div>

                    {{-- Right: Card ilustrasi --}}
                    <div class="col-md-6 d-none d-md-block">
                        <div class="bg-card p-3 shadow-sm">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="text-muted-soft" style="font-size: 0.75rem;">
                                    Ringkasan Modul
                                </div>
                                <span class="badge-soft">
                                    TechFix Dashboard
                                </span>
                            </div>

                            <div class="row g-2 mb-3" style="font-size: 0.8rem;">
                                <div class="col-4">
                                    <div class="bg-dark border border-muted rounded-3 p-2 h-100">
                                        <div class="text-muted-soft" style="font-size: 0.7rem;">Booking</div>
                                        <div class="fw-semibold">Servis</div>
                                        <div class="mt-1" style="height: 4px; border-radius: 999px; background:#f59e0b;"></div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="bg-dark border border-muted rounded-3 p-2 h-100">
                                        <div class="text-muted-soft" style="font-size: 0.7rem;">Sparepart</div>
                                        <div class="fw-semibold">Stok</div>
                                        <div class="mt-1" style="height: 4px; border-radius: 999px; background:#22c55e;"></div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="bg-dark border border-muted rounded-3 p-2 h-100">
                                        <div class="text-muted-soft" style="font-size: 0.7rem;">Keuangan</div>
                                        <div class="fw-semibold">Laba</div>
                                        <div class="mt-1" style="height: 4px; border-radius: 999px; background:#0ea5e9;"></div>
                                    </div>
                                </div>
                            </div>

                            <hr class="border-muted mb-3">

                            <div class="text-muted-soft" style="font-size: 0.75rem;">
                                Sistem ini mendukung:
                                <ul class="mt-1 ps-3 mb-0">
                                    <li>Manajemen antrian servis & status pengerjaan</li>
                                    <li>Pencatatan pemakaian & penjualan sparepart</li>
                                    <li>Laporan laba rugi bengkel komputer</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        {{-- Footer --}}
        <footer class="py-3">
            <div class="container d-flex justify-content-between" style="font-size: 0.75rem; color:#9ca3af;">
                <span>&copy; {{ date('Y') }} TechFix Service &amp; Sparepart.</span>
                <span>Devolopment Area (Demo).</span>
            </div>
        </footer>
    </div>
</div>


<script src="{{ asset('bootstrap/bootstraps/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>