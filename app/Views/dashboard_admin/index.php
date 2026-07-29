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
            transition: all 0.3s ease;
        }

        .notification-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="mb-6 flex justify-between items-center flex-wrap gap-4">
        <div>
            <h2 class="text-3xl font-bold text-gray-800 mb-1">Dashboard Utama</h2>
            <p class="text-gray-600 text-sm">Ringkasan rinci arus kas, pembiayaan, transaksi umum, dan performa koperasi</p>
        </div>
        <div class="bg-white px-4 py-2 rounded-lg shadow-sm border border-gray-200 text-xs font-semibold text-gray-600">
            <i class="fas fa-clock text-emerald-600 mr-1.5"></i>Update: <?= date('d M Y, H:i') ?> WIB
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
            <p class="text-xs text-amber-100 mt-2 opacity-90 relative z-10"><i class="fas fa-chart-pie mr-1"></i>Margin + Umum - Expense</p>
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

        <!-- CARD 7: Pemasukan Umum Bulan Ini -->
        <div class="stat-card bg-white p-5 rounded-2xl shadow-sm border-l-4 border-emerald-500 border-y border-r border-gray-100 flex flex-col justify-between min-h-[135px]">
            <div class="flex justify-between items-start gap-2">
                <div class="flex-1 min-w-0">
                    <p class="text-gray-400 text-xs font-bold uppercase tracking-wider">Pemasukan (Bulan Ini)</p>
                    <h3 class="text-2xl font-extrabold text-emerald-600 mt-1 tracking-tight">Rp <?= number_format($pemasukanBulanIni ?? 0, 0, ',', '.') ?></h3>
                </div>
                <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl shrink-0">
                    <i class="fas fa-arrow-circle-down text-xl"></i>
                </div>
            </div>
            <p class="text-xs text-emerald-600 font-medium mt-2"><i class="fas fa-arrow-up mr-1"></i>Pemasukan Operasional</p>
        </div>

        <!-- CARD 8: Pengeluaran Umum Bulan Ini -->
        <div class="stat-card bg-white p-5 rounded-2xl shadow-sm border-l-4 border-rose-500 border-y border-r border-gray-100 flex flex-col justify-between min-h-[135px]">
            <div class="flex justify-between items-start gap-2">
                <div class="flex-1 min-w-0">
                    <p class="text-gray-400 text-xs font-bold uppercase tracking-wider">Pengeluaran (Bulan Ini)</p>
                    <h3 class="text-2xl font-extrabold text-rose-600 mt-1 tracking-tight">Rp <?= number_format($pengeluaranBulanIni ?? 0, 0, ',', '.') ?></h3>
                </div>
                <div class="p-3 bg-rose-50 text-rose-600 rounded-xl shrink-0">
                    <i class="fas fa-arrow-circle-up text-xl"></i>
                </div>
            </div>
            <p class="text-xs text-rose-600 font-medium mt-2"><i class="fas fa-arrow-down mr-1"></i>Beban Operasional</p>
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

        <!-- CARD 10: Total Potensi Margin (Baru) -->
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

        <!-- CARD 12: Rasio Penyaluran (FDR/LDR) (Baru - Penyeimbang Layout) -->
        <?php 
            // Hitung Rasio Pembiayaan berbanding Simpanan
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
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Chart -->
        <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-100">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-chart-area text-emerald-600"></i>Perkembangan Simpanan & Pembiayaan
            </h3>
            <div class="h-64">
                <canvas id="growthChart"></canvas>
            </div>
        </div>

        <!-- Notifikasi Penting -->
        <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-100">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-bell text-amber-500"></i>Notifikasi Penting
            </h3>
            <div class="space-y-3">
                <a href="<?= base_url('admin/pending-members') ?>" class="block notification-card">
                    <div class="flex items-center p-3.5 bg-green-50 border-l-4 border-green-500 rounded-xl hover:bg-green-100/80 transition-colors">
                        <i class="fas fa-user-plus text-green-500 text-lg mr-3"></i>
                        <div class="flex-1">
                            <p class="font-bold text-green-900 text-sm"><?= $pendingCount ?? 0 ?> Anggota Baru</p>
                            <p class="text-xs text-green-700">Menunggu verifikasi</p>
                        </div>
                        <i class="fas fa-chevron-right text-green-400 text-xs"></i>
                    </div>
                </a>

                <a href="<?= base_url('admin/pending-sukarela') ?>" class="block notification-card">
                    <div class="flex items-center p-3.5 bg-yellow-50 border-l-4 border-yellow-500 rounded-xl hover:bg-yellow-100/80 transition-colors">
                        <i class="fas fa-hourglass-half text-yellow-500 text-lg mr-3"></i>
                        <div class="flex-1">
                            <p class="font-bold text-yellow-900 text-sm"><?= $pendingSimpananCount ?? 0 ?> Simpanan Sukarela</p>
                            <p class="text-xs text-yellow-700">Menunggu persetujuan admin</p>
                        </div>
                        <i class="fas fa-chevron-right text-yellow-400 text-xs"></i>
                    </div>
                </a>

                <a href="<?= base_url('admin/pending-simpanan-pokok') ?>" class="block notification-card">
                    <div class="flex items-center p-3.5 bg-amber-50 border-l-4 border-amber-500 rounded-xl hover:bg-amber-100/80 transition-colors">
                        <i class="fas fa-landmark text-amber-500 text-lg mr-3"></i>
                        <div class="flex-1">
                            <p class="font-bold text-amber-900 text-sm"><?= $pendingSimpananPokokCount ?? 0 ?> Simpanan Pokok</p>
                            <p class="text-xs text-amber-700">Menunggu verifikasi</p>
                        </div>
                        <i class="fas fa-chevron-right text-amber-400 text-xs"></i>
                    </div>
                </a>

                <a href="<?= base_url('admin/pending-pinjaman') ?>" class="block notification-card">
                    <div class="flex items-center p-3.5 bg-orange-50 border-l-4 border-orange-500 rounded-xl hover:bg-orange-100/80 transition-colors">
                        <i class="fas fa-money-check-alt text-orange-500 text-lg mr-3"></i>
                        <div class="flex-1">
                            <p class="font-bold text-orange-900 text-sm"><?= $pendingPinjamanCount ?? 0 ?> Pinjaman</p>
                            <p class="text-xs text-orange-700">Belum disetujui oleh admin</p>
                        </div>
                        <i class="fas fa-chevron-right text-orange-400 text-xs"></i>
                    </div>
                </a>

                <a href="<?= base_url('admin/pembayaran-pending') ?>" class="block notification-card">
                    <div class="flex items-center p-3.5 bg-purple-50 border-l-4 border-purple-500 rounded-xl hover:bg-purple-100/80 transition-colors">
                        <i class="fas fa-credit-card text-purple-500 text-lg mr-3"></i>
                        <div class="flex-1">
                            <p class="font-bold text-purple-900 text-sm"><?= $pendingPembayaranCount ?? 0 ?> Pembayaran Cicilan</p>
                            <p class="text-xs text-purple-700">Menunggu verifikasi admin</p>
                        </div>
                        <i class="fas fa-chevron-right text-purple-400 text-xs"></i>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-100">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Aksi Cepat</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="<?= base_url('admin/members') ?>" class="flex items-center p-4 bg-blue-50/80 rounded-xl hover:bg-blue-100 transition-colors border border-blue-100">
                <i class="fas fa-users text-blue-600 text-xl mr-3"></i>
                <span class="font-bold text-sm text-blue-900">Kelola Anggota</span>
            </a>
            <a href="<?= base_url('admin/savings') ?>" class="flex items-center p-4 bg-emerald-50/80 rounded-xl hover:bg-emerald-100 transition-colors border border-emerald-100">
                <i class="fas fa-coins text-emerald-600 text-xl mr-3"></i>
                <span class="font-bold text-sm text-emerald-900">Kelola Simpanan</span>
            </a>
            <a href="<?= base_url('admin/financing') ?>" class="flex items-center p-4 bg-purple-50/80 rounded-xl hover:bg-purple-100 transition-colors border border-purple-100">
                <i class="fas fa-hand-holding-usd text-purple-600 text-xl mr-3"></i>
                <span class="font-bold text-sm text-purple-900">Kelola Pembiayaan</span>
            </a>
            <a href="<?= base_url('admin/transactions') ?>" class="flex items-center p-4 bg-orange-50/80 rounded-xl hover:bg-orange-100 transition-colors border border-orange-100">
                <i class="fas fa-receipt text-orange-600 text-xl mr-3"></i>
                <span class="font-bold text-sm text-orange-900">Transaksi Umum</span>
            </a>
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