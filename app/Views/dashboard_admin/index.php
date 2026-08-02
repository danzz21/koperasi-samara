<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Utama</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .stat-card {
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.08);
        }

        .notification-card {
            transition: all 0.2s ease;
        }

        .notification-card:hover {
            transform: translateX(4px);
        }
    </style>
</head>

<body class="bg-gray-50">
    <!-- Header dengan Filter Waktu Dinamis -->
    <div class="mb-6 flex justify-between items-center flex-wrap gap-4">
        <div>
            <h2 class="text-3xl font-bold text-gray-800 mb-1">Dashboard Utama</h2>
            <p class="text-gray-600 text-sm">Ringkasan rinci arus kas, pembiayaan, transaksi umum, dan performa koperasi</p>
        </div>

        <div class="flex items-center gap-3">
            <!-- Filter Dropdown -->
            <form method="GET" action="" id="filterForm" class="flex items-center gap-2 bg-white px-3 py-1.5 rounded-xl shadow-sm border border-gray-200">
                <i class="fas fa-filter text-emerald-600 text-xs"></i>
                <label for="filterSelect" class="text-xs font-semibold text-gray-600 hidden sm:inline">Periode Operasional:</label>
                <select name="filter" id="filterSelect" onchange="document.getElementById('filterForm').submit()" class="bg-transparent text-xs font-bold text-emerald-700 focus:outline-none cursor-pointer">
                    <option value="tahun" <?= ($filterActive ?? 'tahun') === 'tahun' ? 'selected' : '' ?>>Tahun Ini (<?= date('Y') ?>)</option>
                    <option value="bulan" <?= ($filterActive ?? '') === 'bulan' ? 'selected' : '' ?>>Bulan Ini (<?= date('F') ?>)</option>
                    <option value="minggu" <?= ($filterActive ?? '') === 'minggu' ? 'selected' : '' ?>>Minggu Ini</option>
                    <option value="hari" <?= ($filterActive ?? '') === 'hari' ? 'selected' : '' ?>>Hari Ini</option>
                </select>
            </form>

            <div class="bg-white px-4 py-2 rounded-xl shadow-sm border border-gray-200 text-xs font-semibold text-gray-600 hidden md:block">
                <i class="fas fa-clock text-emerald-600 mr-1.5"></i><?= date('d M Y, H:i') ?> WIB
            </div>
        </div>
    </div>


    <!-- STAT CARDS GRID (PERFECT 4 - 4 - 4 LAYOUT) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">

        <!-- ================= BARIS 1: ARUS KAS & KEUANGAN UTAMA ================= -->

        <!-- CARD 1: Kas Real / Saldo Fisik -->
        <div class="stat-card bg-gradient-to-br from-emerald-600 to-teal-700 text-white p-5 rounded-2xl shadow-lg relative overflow-hidden flex flex-col justify-between min-h-[135px]">
            <div class="flex justify-between items-start relative z-10 gap-2">
                <div class="flex-1 min-w-0">
                    <p class="text-emerald-100 text-xs font-bold uppercase tracking-wider">Saldo Kas Real (Fisik)</p>
                    <h3 class="text-2xl font-extrabold mt-1 tracking-tight">Rp <?= number_format($kasReal ?? 0, 0, ',', '.') ?></h3>
                </div>
                <div class="p-3 bg-white/20 backdrop-blur-md rounded-xl text-white shrink-0">
                    <i class="fas fa-vault text-xl"></i>
                </div>
            </div>
            <p class="text-xs text-emerald-100 mt-2 opacity-90 relative z-10"><i class="fas fa-wallet mr-1"></i>Uang Kas Siap Pakai</p>
            <div class="absolute -right-4 -bottom-4 w-20 h-20 bg-white/10 rounded-full blur-xl"></div>
        </div>

        <!-- CARD 2: Estimasi SHU Berjalan -->
        <div class="stat-card bg-gradient-to-br from-amber-500 to-orange-600 text-white p-5 rounded-2xl shadow-lg relative overflow-hidden flex flex-col justify-between min-h-[135px]">
            <div class="flex justify-between items-start relative z-10 gap-2">
                <div class="flex-1 min-w-0">
                    <p class="text-amber-100 text-xs font-bold uppercase tracking-wider">Estimasi SHU Berjalan</p>
                    <h3 class="text-2xl font-extrabold mt-1 tracking-tight">Rp <?= number_format($shuTahunBerjalan ?? 0, 0, ',', '.') ?></h3>
                </div>
                <div class="p-3 bg-white/20 backdrop-blur-md rounded-xl text-white shrink-0">
                    <i class="fas fa-coins text-xl"></i>
                </div>
            </div>
            <p class="text-xs text-amber-100 mt-2 opacity-90 relative z-10"><i class="fas fa-chart-pie mr-1"></i>Margin + Umum - Expense (<?= esc($filterLabel) ?>)</p>
            <div class="absolute -right-4 -bottom-4 w-20 h-20 bg-white/10 rounded-full blur-xl"></div>
        </div>

        <!-- CARD 3: Valuasi Total Aset -->
        <div class="stat-card bg-white p-5 rounded-2xl shadow-sm border-l-4 border-cyan-500 border-y border-r border-gray-100 flex flex-col justify-between min-h-[135px]">
            <div class="flex justify-between items-start gap-2">
                <div class="flex-1 min-w-0">
                    <p class="text-gray-400 text-xs font-bold uppercase tracking-wider">Valuasi Total Aset</p>
                    <h3 class="text-2xl font-extrabold text-gray-800 mt-1 tracking-tight">Rp <?= number_format($totalAset ?? 0, 0, ',', '.') ?></h3>
                </div>
                <div class="p-3 bg-cyan-50 text-cyan-600 rounded-xl shrink-0">
                    <i class="fas fa-landmark text-xl"></i>
                </div>
            </div>
            <p class="text-xs text-cyan-600 font-medium mt-2"><i class="fas fa-building mr-1"></i>Kas Real + Piutang Pokok</p>
        </div>

        <!-- CARD 4: Total Simpanan Anggota -->
        <div class="stat-card bg-white p-5 rounded-2xl shadow-sm border-l-4 border-blue-500 border-y border-r border-gray-100 flex flex-col justify-between min-h-[135px]">
            <div class="flex justify-between items-start gap-2">
                <div class="flex-1 min-w-0">
                    <p class="text-gray-400 text-xs font-bold uppercase tracking-wider">Total Simpanan</p>
                    <h3 class="text-2xl font-extrabold text-gray-800 mt-1 tracking-tight">Rp <?= number_format($totalSimpanan ?? 0, 0, ',', '.') ?></h3>
                </div>
                <div class="p-3 bg-blue-50 text-blue-600 rounded-xl shrink-0">
                    <i class="fas fa-wallet text-xl"></i>
                </div>
            </div>
            <p class="text-xs text-blue-600 font-medium mt-2"><i class="fas fa-coins mr-1"></i>Pokok + Wajib + Sukarela</p>
        </div>


        <!-- ================= BARIS 2: PEMBIAYAAN & OPERASIONAL ================= -->

        <!-- CARD 5: Pokok Pinjaman Beredar -->
        <div class="stat-card bg-white p-5 rounded-2xl shadow-sm border-l-4 border-amber-500 border-y border-r border-gray-100 flex flex-col justify-between min-h-[135px]">
            <div class="flex justify-between items-start gap-2">
                <div class="flex-1 min-w-0">
                    <p class="text-gray-400 text-xs font-bold uppercase tracking-wider">Pokok Beredar</p>
                    <h3 class="text-2xl font-extrabold text-gray-800 mt-1 tracking-tight">Rp <?= number_format($sisaPokokPinjaman ?? 0, 0, ',', '.') ?></h3>
                </div>
                <div class="p-3 bg-amber-50 text-amber-600 rounded-xl shrink-0">
                    <i class="fas fa-file-invoice-dollar text-xl"></i>
                </div>
            </div>
            <p class="text-xs text-amber-600 font-medium mt-2"><i class="fas fa-hand-holding-usd mr-1"></i>Uang Pokok di Anggota</p>
        </div>

        <!-- CARD 6: Realisasi Margin (Profit Terkumpul) -->
        <div class="stat-card bg-white p-5 rounded-2xl shadow-sm border-l-4 border-indigo-500 border-y border-r border-gray-100 flex flex-col justify-between min-h-[135px]">
            <div class="flex justify-between items-start gap-2">
                <div class="flex-1 min-w-0">
                    <p class="text-gray-400 text-xs font-bold uppercase tracking-wider">Profit Realisasi Margin</p>
                    <h3 class="text-2xl font-extrabold text-gray-800 mt-1 tracking-tight">Rp <?= number_format($realisasiMargin ?? 0, 0, ',', '.') ?></h3>
                </div>
                <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl shrink-0">
                    <i class="fas fa-chart-line text-xl"></i>
                </div>
            </div>
            <p class="text-xs text-indigo-600 font-medium mt-2"><i class="fas fa-check-circle mr-1"></i>Margin Masuk Angsuran</p>
        </div>

        <!-- CARD 7: Pemasukan Operasional (SESUAI FILTER) -->
        <div class="stat-card bg-white p-5 rounded-2xl shadow-sm border-l-4 border-emerald-500 border-y border-r border-gray-100 flex flex-col justify-between min-h-[135px]">
            <div class="flex justify-between items-start gap-2">
                <div class="flex-1 min-w-0">
                    <p class="text-gray-400 text-xs font-bold uppercase tracking-wider">Pemasukan Operasional</p>
                    <h3 class="text-2xl font-extrabold text-emerald-600 mt-1 tracking-tight">Rp <?= number_format($pemasukanFiltered ?? 0, 0, ',', '.') ?></h3>
                </div>
                <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl shrink-0">
                    <i class="fas fa-arrow-circle-down text-xl"></i>
                </div>
            </div>
            <p class="text-xs text-emerald-600 font-medium mt-2"><i class="fas fa-calendar-check mr-1"></i><?= esc($filterLabel) ?></p>
        </div>

        <!-- CARD 8: Beban Operasional (SESUAI FILTER) -->
        <div class="stat-card bg-white p-5 rounded-2xl shadow-sm border-l-4 border-rose-500 border-y border-r border-gray-100 flex flex-col justify-between min-h-[135px]">
            <div class="flex justify-between items-start gap-2">
                <div class="flex-1 min-w-0">
                    <p class="text-gray-400 text-xs font-bold uppercase tracking-wider">Beban Operasional</p>
                    <h3 class="text-2xl font-extrabold text-rose-600 mt-1 tracking-tight">Rp <?= number_format($pengeluaranFiltered ?? 0, 0, ',', '.') ?></h3>
                </div>
                <div class="p-3 bg-rose-50 text-rose-600 rounded-xl shrink-0">
                    <i class="fas fa-arrow-circle-up text-xl"></i>
                </div>
            </div>
            <p class="text-xs text-rose-600 font-medium mt-2"><i class="fas fa-calendar-check mr-1"></i><?= esc($filterLabel) ?></p>
        </div>


        <!-- ================= BARIS 3: PROYEKSI, ANGGOTA & KESEHATAN ================= -->

        <!-- CARD 9: Estimasi Tagihan Bulan Ini -->
        <div class="stat-card bg-white p-5 rounded-2xl shadow-sm border-l-4 border-purple-500 border-y border-r border-gray-100 flex flex-col justify-between min-h-[135px]">
            <div class="flex justify-between items-start gap-2">
                <div class="flex-1 min-w-0">
                    <p class="text-gray-400 text-xs font-bold uppercase tracking-wider">Tagihan Bulan Ini</p>
                    <h3 class="text-2xl font-extrabold text-gray-800 mt-1 tracking-tight">Rp <?= number_format($tagihanBulanIni ?? 0, 0, ',', '.') ?></h3>
                </div>
                <div class="p-3 bg-purple-50 text-purple-600 rounded-xl shrink-0">
                    <i class="fas fa-receipt text-xl"></i>
                </div>
            </div>
            <p class="text-xs text-purple-600 font-medium mt-2"><i class="fas fa-calendar-alt mr-1"></i>Ekspektasi Arus Masuk</p>
        </div>

        <!-- CARD 10: Total Potensi Margin -->
        <div class="stat-card bg-white p-5 rounded-2xl shadow-sm border-l-4 border-teal-500 border-y border-r border-gray-100 flex flex-col justify-between min-h-[135px]">
            <div class="flex justify-between items-start gap-2">
                <div class="flex-1 min-w-0">
                    <p class="text-gray-400 text-xs font-bold uppercase tracking-wider">Potensi Margin Aktif</p>
                    <h3 class="text-2xl font-extrabold text-gray-800 mt-1 tracking-tight">Rp <?= number_format($potensiMargin ?? 0, 0, ',', '.') ?></h3>
                </div>
                <div class="p-3 bg-teal-50 text-teal-600 rounded-xl shrink-0">
                    <i class="fas fa-bullseye text-xl"></i>
                </div>
            </div>
            <p class="text-xs text-teal-600 font-medium mt-2"><i class="fas fa-percentage mr-1"></i>Target Margin Pinjaman</p>
        </div>

        <!-- CARD 11: Total Anggota Aktif -->
        <div class="stat-card bg-white p-5 rounded-2xl shadow-sm border-l-4 border-slate-600 border-y border-r border-gray-100 flex flex-col justify-between min-h-[135px]">
            <div class="flex justify-between items-start gap-2">
                <div class="flex-1 min-w-0">
                    <p class="text-gray-400 text-xs font-bold uppercase tracking-wider">Total Anggota Aktif</p>
                    <h3 class="text-2xl font-extrabold text-gray-800 mt-1 tracking-tight"><?= number_format($totalAnggota ?? 0) ?> <span class="text-base font-semibold text-gray-500">Orang</span></h3>
                </div>
                <div class="p-3 bg-slate-100 text-slate-700 rounded-xl shrink-0">
                    <i class="fas fa-users text-xl"></i>
                </div>
            </div>
            <p class="text-xs text-slate-600 font-medium mt-2"><i class="fas fa-user-check mr-1"></i>Terverifikasi Aktif</p>
        </div>

        <!-- CARD 12: Rasio Penyaluran (FDR/LDR) -->
        <?php 
            $rasioPembiayaan = $totalSimpanan > 0 ? round(($sisaPokokPinjaman / $totalSimpanan) * 100, 1) : 0;
        ?>
        <div class="stat-card bg-white p-5 rounded-2xl shadow-sm border-l-4 border-violet-500 border-y border-r border-gray-100 flex flex-col justify-between min-h-[135px]">
            <div class="flex justify-between items-start gap-2">
                <div class="flex-1 min-w-0">
                    <p class="text-gray-400 text-xs font-bold uppercase tracking-wider">Rasio Pembiayaan</p>
                    <h3 class="text-2xl font-extrabold text-gray-800 mt-1 tracking-tight"><?= $rasioPembiayaan ?>%</h3>
                </div>
                <div class="p-3 bg-violet-50 text-violet-600 rounded-xl shrink-0">
                    <i class="fas fa-tachometer-alt text-xl"></i>
                </div>
            </div>
            <p class="text-xs text-violet-600 font-medium mt-2"><i class="fas fa-sliders-h mr-1"></i>Pinjaman vs Simpanan</p>
        </div>

    </div>

    <!-- Charts and Notifications -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Chart (2 Kolom di Desktop) -->
        <div class="lg:col-span-2 bg-white p-6 rounded-2xl shadow-md border border-gray-100">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-chart-area text-emerald-600"></i>Perkembangan Simpanan & Pembiayaan Tahun Ini
            </h3>
            <div class="h-72">
                <canvas id="growthChart"></canvas>
            </div>
        </div>

        <!-- Notifikasi Penting (1 Kolom Dipercantik) -->
        <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-100 flex flex-col justify-between">
            <div>
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-bell text-amber-500"></i>Persetujuan Pending
                    </h3>
                    <span class="text-xs bg-amber-100 text-amber-800 px-2.5 py-1 rounded-full font-bold">
                        Total: <?= ($pendingCount + $pendingSimpananCount + $pendingSimpananPokokCount + $pendingPinjamanCount + $pendingPembayaranCount) ?>
                    </span>
                </div>

                <div class="space-y-2.5">
                    <a href="<?= base_url('admin/pending-members') ?>" class="block notification-card">
                        <div class="flex items-center justify-between p-3 bg-gray-50 hover:bg-emerald-50/60 border border-gray-100 hover:border-emerald-200 rounded-xl transition-all">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center mr-3 font-bold text-xs">
                                    <i class="fas fa-user-plus"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-800 text-xs">Anggota Baru</p>
                                    <p class="text-[11px] text-gray-500">Verifikasi pendaftaran</p>
                                </div>
                            </div>
                            <span class="px-2.5 py-1 text-xs font-extrabold rounded-lg <?= ($pendingCount > 0) ? 'bg-emerald-500 text-white' : 'bg-gray-200 text-gray-600' ?>">
                                <?= $pendingCount ?? 0 ?>
                            </span>
                        </div>
                    </a>

                    <a href="<?= base_url('admin/pending-sukarela') ?>" class="block notification-card">
                        <div class="flex items-center justify-between p-3 bg-gray-50 hover:bg-blue-50/60 border border-gray-100 hover:border-blue-200 rounded-xl transition-all">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center mr-3 font-bold text-xs">
                                    <i class="fas fa-wallet"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-800 text-xs">Simpanan Sukarela</p>
                                    <p class="text-[11px] text-gray-500">Konfirmasi setoran</p>
                                </div>
                            </div>
                            <span class="px-2.5 py-1 text-xs font-extrabold rounded-lg <?= ($pendingSimpananCount > 0) ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-600' ?>">
                                <?= $pendingSimpananCount ?? 0 ?>
                            </span>
                        </div>
                    </a>

                    <a href="<?= base_url('admin/pending-simpanan-pokok') ?>" class="block notification-card">
                        <div class="flex items-center justify-between p-3 bg-gray-50 hover:bg-indigo-50/60 border border-gray-100 hover:border-indigo-200 rounded-xl transition-all">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center mr-3 font-bold text-xs">
                                    <i class="fas fa-landmark"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-800 text-xs">Simpanan Pokok</p>
                                    <p class="text-[11px] text-gray-500">Verifikasi pokok awal</p>
                                </div>
                            </div>
                            <span class="px-2.5 py-1 text-xs font-extrabold rounded-lg <?= ($pendingSimpananPokokCount > 0) ? 'bg-indigo-500 text-white' : 'bg-gray-200 text-gray-600' ?>">
                                <?= $pendingSimpananPokokCount ?? 0 ?>
                            </span>
                        </div>
                    </a>

                    <a href="<?= base_url('admin/pending-pinjaman') ?>" class="block notification-card">
                        <div class="flex items-center justify-between p-3 bg-gray-50 hover:bg-amber-50/60 border border-gray-100 hover:border-amber-200 rounded-xl transition-all">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center mr-3 font-bold text-xs">
                                    <i class="fas fa-file-invoice-dollar"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-800 text-xs">Pengajuan Pinjaman</p>
                                    <p class="text-[11px] text-gray-500">Persetujuan akad</p>
                                </div>
                            </div>
                            <span class="px-2.5 py-1 text-xs font-extrabold rounded-lg <?= ($pendingPinjamanCount > 0) ? 'bg-amber-500 text-white' : 'bg-gray-200 text-gray-600' ?>">
                                <?= $pendingPinjamanCount ?? 0 ?>
                            </span>
                        </div>
                    </a>

                    <a href="<?= base_url('admin/pembayaran-pending') ?>" class="block notification-card">
                        <div class="flex items-center justify-between p-3 bg-gray-50 hover:bg-purple-50/60 border border-gray-100 hover:border-purple-200 rounded-xl transition-all">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center mr-3 font-bold text-xs">
                                    <i class="fas fa-credit-card"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-800 text-xs">Pembayaran Cicilan</p>
                                    <p class="text-[11px] text-gray-500">Setoran angsuran</p>
                                </div>
                            </div>
                            <span class="px-2.5 py-1 text-xs font-extrabold rounded-lg <?= ($pendingPembayaranCount > 0) ? 'bg-purple-500 text-white' : 'bg-gray-200 text-gray-600' ?>">
                                <?= $pendingPembayaranCount ?? 0 ?>
                            </span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Script untuk Chart -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById('growthChart').getContext('2d');
            const growthChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: <?= $chartLabels ?? '[]' ?>,
                    datasets: [{
                            label: 'Simpanan',
                            data: <?= $chartSimpanan ?? '[]' ?>,
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            borderWidth: 2,
                            tension: 0.4,
                            fill: true
                        },
                        {
                            label: 'Pembiayaan',
                            data: <?= $chartPembiayaan ?? '[]' ?>,
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            borderWidth: 2,
                            tension: 0.4,
                            fill: true
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) label += ': ';
                                    if (context.parsed.y !== null) {
                                        label += new Intl.NumberFormat('id-ID', {
                                            style: 'currency',
                                            currency: 'IDR',
                                            minimumFractionDigits: 0
                                        }).format(context.parsed.y);
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    if (value >= 1000000) {
                                        return 'Rp ' + (value / 1000000).toFixed(1) + 'Jt';
                                    } else if (value >= 1000) {
                                        return 'Rp ' + (value / 1000).toFixed(0) + 'Rb';
                                    }
                                    return 'Rp ' + value;
                                }
                            }
                        }
                    },
                    interaction: {
                        mode: 'nearest',
                        axis: 'x',
                        intersect: false
                    }
                }
            });
        });
    </script>
</body>

</html>