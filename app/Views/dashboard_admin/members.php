<!-- TOAST NOTIFICATION UI (Ganti Alert Browser) -->
<div id="toastNotification" class="fixed top-5 right-5 z-50 hidden max-w-xs w-full bg-white rounded-xl shadow-2xl border p-4 transition-all duration-300 transform translate-y-[-10px]">
    <div class="flex items-center gap-3">
        <div id="toastIcon" class="text-lg"></div>
        <div class="flex-1">
            <h4 id="toastTitle" class="font-bold text-xs text-gray-800">Pemberitahuan</h4>
            <p id="toastMessage" class="text-[11px] text-gray-600 mt-0.5"></p>
        </div>
        <button onclick="hideToast()" class="text-gray-400 hover:text-gray-600 text-xs cursor-pointer">
            <i class="fas fa-times"></i>
        </button>
    </div>
</div>

<?php $anggota = $anggota ?? []; ?>
<!-- Header Halaman -->
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h2 class="text-2xl font-extrabold text-gray-800 tracking-tight"><?= $title ?? 'Manajemen Anggota' ?></h2>
        <p class="text-xs text-gray-500 mt-1">Kelola data anggota aktif, berkas dokumen, dan pendaftaran anggota baru</p>
    </div>
    <button onclick="openAddMemberModal()" class="inline-flex items-center justify-center bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2.5 rounded-xl font-bold text-xs shadow-md shadow-emerald-600/20 transition-all cursor-pointer">
        <i class="fas fa-user-plus mr-2"></i>Tambah Anggota Baru
    </button>
</div>

<!-- Table Container -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
    <div class="p-5 border-b border-gray-100 bg-gray-50/50 flex flex-wrap items-center justify-between gap-4">
        <h3 class="text-sm font-bold text-gray-800 flex items-center">
            <i class="fas fa-users text-emerald-600 mr-2"></i>Daftar Anggota Koperasi
        </h3>

        <form method="GET" action="" onsubmit="return false;">
            <div class="relative w-full md:w-80">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400 text-xs">
                    <i class="fas fa-search"></i>
                </span>
                <input
                    type="text"
                    id="searchInput"
                    name="search"
                    value="<?= htmlspecialchars($search ?? '') ?>"
                    placeholder="Cari nama, No. KTP, No. Anggota..."
                    autocomplete="off"
                    class="w-full pl-9 pr-4 py-2 bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-xs transition" />
            </div>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/80 text-[11px] font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">
                    <th class="px-6 py-3.5">No</th>
                    <th class="px-6 py-3.5">Nama Anggota</th>
                    <th class="px-6 py-3.5">No. KTP</th>
                    <th class="px-6 py-3.5">Status</th>
                    <th class="px-6 py-3.5">Tgl Daftar</th>
                    <th class="px-6 py-3.5 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-xs" id="anggotaTableBody">
                <?php if (!isset($search) || $search === ''): ?>
                    <?php $nomor = 1;
                    foreach ($anggota as $data):
                        $id_anggota = $data['id_anggota'];
                        $id_user    = $data['id_user'] ?? $id_anggota;
                        $nama       = ucwords($data['nama_lengkap']);
                        $ktp        = $data['no_ktp'];
                        $status     = $data['status'] ?? 'Menunggu Verifikasi';
                        $tanggal    = isset($data['tanggal_daftar']) ? date("d M Y", strtotime($data['tanggal_daftar'])) : '-';
                        $urlDetail  = base_url('admin/detail-anggota/' . $id_anggota);

                        $isAktif = strtolower($status) == 'aktif';
                        $badge = $isAktif ?
                            "<span class='px-2.5 py-1 inline-flex text-[10px] leading-tight font-extrabold rounded-full bg-emerald-100 text-emerald-800 border border-emerald-200'>$status</span>" :
                            "<span class='px-2.5 py-1 inline-flex text-[10px] leading-tight font-extrabold rounded-full bg-amber-100 text-amber-800 border border-amber-200'>$status</span>";

                        $canReset = !empty($id_user);
                        $resetClass = $canReset ? 'text-amber-600 hover:text-amber-800 hover:bg-amber-50 p-1.5 rounded-lg transition-colors' : 'text-gray-300 cursor-not-allowed p-1.5';
                        $resetTitle = $canReset ? 'Reset Password' : 'User tidak ditemukan';
                    ?>
                        <tr class='hover:bg-emerald-50/30 transition duration-150 cursor-pointer' onclick="window.location.href='<?= $urlDetail ?>'">
                            <td class='px-6 py-4 font-medium text-gray-500'><?= $nomor++ ?></td>
                            <td class='px-6 py-4 font-bold text-gray-800'><?= $nama ?></td>
                            <td class='px-6 py-4 text-gray-600 font-mono text-[11px]'><?= $ktp ?></td>
                            <td class='px-6 py-4'><?= $badge ?></td>
                            <td class='px-6 py-4 text-gray-500'><?= $tanggal ?></td>
                            <td class='px-6 py-4 text-center space-x-1' onclick="event.stopPropagation()">
                                <button type="button"
                                    onclick="openEditMemberModal(this)"
                                    class='text-blue-600 hover:text-blue-800 p-1.5 hover:bg-blue-50 rounded-lg transition-colors'
                                    data-id="<?= $id_anggota ?>"
                                    data-name="<?= htmlspecialchars($data['nama_lengkap'], ENT_QUOTES, 'UTF-8') ?>"
                                    data-ktp="<?= htmlspecialchars($data['no_ktp'], ENT_QUOTES, 'UTF-8') ?>"
                                    data-hp="<?= htmlspecialchars($data['no_hp'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                    data-status="<?= htmlspecialchars($status, ENT_QUOTES, 'UTF-8') ?>"
                                    data-tanggal="<?= htmlspecialchars($data['tanggal_daftar'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                    title="Edit Data">
                                    <i class='fas fa-edit'></i>
                                </button>

                                <button onclick="showDeleteModal(<?= $id_anggota ?>)"
                                    class='text-rose-600 hover:text-rose-800 p-1.5 hover:bg-rose-50 rounded-lg transition-colors'
                                    title="Hapus Anggota">
                                    <i class='fas fa-trash'></i>
                                </button>

                                <button onclick="window.location.href='<?= $urlDetail ?>'"
                                    class='text-emerald-600 hover:text-emerald-800 p-1.5 hover:bg-emerald-50 rounded-lg transition-colors'
                                    title="Lihat Detail">
                                    <i class='fas fa-eye'></i>
                                </button>

                                <?php if ($canReset): ?>
                                    <button onclick="resetPassword(<?= $id_user ?>, '<?= htmlspecialchars($nama) ?>')" class='<?= $resetClass ?>' title="<?= $resetTitle ?>">
                                        <i class='fas fa-key'></i>
                                    </button>
                                <?php else: ?>
                                    <button class='<?= $resetClass ?>' title="<?= $resetTitle ?>" disabled>
                                        <i class='fas fa-key'></i>
                                    </button>
                                <?php endif; ?>

                                <button onclick="toggleMemberStatus(<?= $id_anggota ?>, '<?= htmlspecialchars($nama) ?>', '<?= $status ?>')"
                                    class='p-1.5 rounded-lg transition-colors <?= strtolower($status) == 'nonaktif' ? 'text-emerald-600 hover:bg-emerald-50' : 'text-orange-600 hover:bg-orange-50' ?>'
                                    title="<?= strtolower($status) == 'nonaktif' ? 'Aktifkan Anggota' : 'Nonaktifkan Anggota' ?>">
                                    <i class='fas <?= strtolower($status) == 'nonaktif' ? 'fa-check-circle' : 'fa-times-circle' ?>'></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<!-- MODAL TAMBAH ANGGOTA (MODERN 2-KOLOM GRID) -->
<div id="memberModal" class="modal fixed inset-0 bg-black/60 items-center justify-center z-50 hidden p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full mx-auto max-h-[90vh] flex flex-col overflow-hidden">

        <!-- Header Modal -->
        <div class="px-6 py-4 bg-gradient-to-r from-emerald-800 to-teal-700 text-white flex items-center justify-between">
            <h3 class="text-sm font-bold flex items-center gap-2">
                <i class="fas fa-user-plus text-amber-300"></i> Pendaftaran Anggota Baru
            </h3>
            <button onclick="closeModal('memberModal')" class="text-white/80 hover:text-white text-lg cursor-pointer">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Form Body -->
        <form id="formMember" class="p-6 overflow-y-auto space-y-5 text-xs" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <!-- Section 1: Akun & Kontak -->
            <div class="space-y-3">
                <h4 class="font-bold text-emerald-800 uppercase tracking-wider text-[11px] border-b border-gray-100 pb-1">
                    <i class="fas fa-id-badge mr-1"></i> Data Akun & Kontak
                </h4>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Nama Lengkap *</label>
                        <input type="text" name="nama_lengkap" required placeholder="Sesuai KTP" class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500" />
                    </div>
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">No. KTP (NIK) *</label>
                        <input type="text" name="no_ktp" required placeholder="16 Digit NIK" class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500" />
                    </div>
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Email *</label>
                        <input type="email" name="email" required placeholder="email@domain.com" class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500" />
                    </div>
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Username *</label>
                        <input type="text" name="username" required placeholder="Username akun" class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500" />
                    </div>
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Password *</label>
                        <input type="password" name="password" required placeholder="Minimal 6 Karakter" class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500" />
                    </div>
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                            <option value="L">Laki-Laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">No. HP Utama *</label>
                        <input type="text" name="no_hp" required placeholder="0812xxxx" class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500" />
                    </div>
                </div>
            </div>

            <!-- Section 2: Pekerjaan & Alamat -->
            <div class="space-y-3 pt-2">
                <h4 class="font-bold text-emerald-800 uppercase tracking-wider text-[11px] border-b border-gray-100 pb-1">
                    <i class="fas fa-briefcase mr-1"></i> Pekerjaan & Alamat Domisili
                </h4>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Pekerjaan</label>
                        <input type="text" name="pekerjaan" placeholder="Contoh: Karyawan / Wiraswasta" class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500" />
                    </div>
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Instansi / Perusahaan</label>
                        <input type="text" name="instansi" placeholder="Nama Tempat Kerja" class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500" />
                    </div>
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Alamat Lengkap *</label>
                    <textarea name="alamat" required placeholder="Alamat sesuai KTP" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"></textarea>
                </div>
            </div>

            <!-- Section 3: Rekening Bank & Setoran Pokok -->
            <div class="space-y-3 pt-2">
                <h4 class="font-bold text-emerald-800 uppercase tracking-wider text-[11px] border-b border-gray-100 pb-1">
                    <i class="fas fa-university mr-1"></i> Rekening Bank & Simpanan
                </h4>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Nama Bank</label>
                        <input type="text" name="jenis_bank" placeholder="BSI / BCA / Mandiri" class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500" />
                    </div>
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">No. Rekening</label>
                        <input type="text" name="no_rek" placeholder="Nomor Rekening" class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500" />
                    </div>
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Atas Nama Rekening</label>
                        <input type="text" name="atasnama_rekening" placeholder="Nama Pemilik Rekening" class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500" />
                    </div>
                </div>

                <div class="bg-emerald-50/50 p-3 rounded-xl border border-emerald-100">
                    <label class="block font-bold text-emerald-800 mb-1">Setoran Simpanan Pokok Awal (Rp)</label>
                    <input type="number" name="simpanan_pokok_awal" placeholder="Contoh: 100000" class="w-full px-3 py-2 bg-white border border-emerald-300 rounded-xl font-bold text-emerald-700 focus:ring-2 focus:ring-emerald-500/20" />
                    <p class="text-[10px] text-emerald-600 mt-1">*Jika diisi, otomatis akan tercatat di histori simpanan pokok anggota.</p>
                </div>
            </div>

            <!-- Section 4: Berkas Dokumen -->
            <div class="space-y-3 pt-2">
                <h4 class="font-bold text-emerald-800 uppercase tracking-wider text-[11px] border-b border-gray-100 pb-1">
                    <i class="fas fa-file-image mr-1"></i> Upload Berkas Foto
                </h4>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Pas Foto Diri</label>
                        <input id="memberFotoDiri" type="file" name="foto_diri" accept="image/*" class="w-full text-xs text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100" />
                        <img id="previewMemberFotoDiri" class="hidden mt-3 h-28 w-full object-cover rounded-xl border border-gray-200" alt="Preview Foto Diri" />
                    </div>
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Foto KTP</label>
                        <input id="memberFotoKtp" type="file" name="foto_ktp" accept="image/*" class="w-full text-xs text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100" />
                        <img id="previewMemberFotoKtp" class="hidden mt-3 h-28 w-full object-cover rounded-xl border border-gray-200" alt="Preview Foto KTP" />
                    </div>
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Selfie + KTP</label>
                        <input id="memberFotoSelfie" type="file" name="foto_diri_ktp" accept="image/*" class="w-full text-xs text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100" />
                        <img id="previewMemberFotoSelfie" class="hidden mt-3 h-28 w-full object-cover rounded-xl border border-gray-200" alt="Preview Selfie KTP" />
                    </div>
                </div>
            </div>

            <!-- Modal Action Buttons -->
            <div class="flex space-x-3 pt-4 border-t border-gray-100">
                <button type="button" onclick="closeModal('memberModal')" class="flex-1 bg-gray-100 text-gray-600 py-2.5 rounded-xl hover:bg-gray-200 transition-colors font-bold cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="flex-1 bg-emerald-600 text-white py-2.5 rounded-xl hover:bg-emerald-700 transition-colors font-bold shadow-md shadow-emerald-600/20 cursor-pointer">
                    Simpan Anggota
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL EDIT ANGGOTA LENGKAP -->
<div id="editMemberModal" class="modal fixed inset-0 bg-black/60 items-center justify-center z-50 hidden p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full mx-auto max-h-[90vh] flex flex-col overflow-hidden">

        <!-- Header Modal -->
        <div class="px-6 py-4 bg-gradient-to-r from-emerald-600 to-emerald-800 text-white flex items-center justify-between">
            <h3 class="text-sm font-bold flex items-center gap-2" id="editMemberTitle">
                <i class="fas fa-user-edit text-white"></i> Edit Anggota
            </h3>
            <button onclick="closeModal('editMemberModal')" class="text-white/80 hover:text-white text-lg cursor-pointer">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Form Body -->
        <form id="editMemberForm" method="post" class="p-6 overflow-y-auto space-y-5 text-xs" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <input type="hidden" name="id_anggota" id="editMemberId" />

            <!-- Section 1: Data Utama & Kontak -->
            <div class="space-y-3">
                <h4 class="font-bold text-blue-800 uppercase tracking-wider text-[11px] border-b border-gray-100 pb-1">
                    <i class="fas fa-id-card mr-1"></i> Identitas & Status
                </h4>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Nama Lengkap *</label>
                        <input type="text" name="nama_lengkap" id="editNamaLengkap" required class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500" />
                    </div>
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">No. KTP (NIK) *</label>
                        <input type="text" name="no_ktp" id="editNoKtp" required class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500" />
                    </div>
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Status Anggota</label>
                        <select name="status" id="editStatus" class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 font-bold">
                            <option value="aktif">Aktif</option>
                            <option value="pending">Menunggu Verifikasi</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Tanggal Daftar</label>
                        <input type="date" name="tanggal_daftar" id="editTanggalDaftar" class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500" />
                    </div>
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Jenis Kelamin</label>
                        <select name="jenis_kelamin" id="editJenisKelamin" class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                            <option value="L">Laki-Laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">No. HP Utama</label>
                        <input type="text" name="no_hp" id="editNoHp" class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500" />
                    </div>
                </div>
            </div>

            <!-- Section 2: Pekerjaan & Alamat -->
            <div class="space-y-3 pt-2">
                <h4 class="font-bold text-blue-800 uppercase tracking-wider text-[11px] border-b border-gray-100 pb-1">
                    <i class="fas fa-briefcase mr-1"></i> Pekerjaan & Domisili
                </h4>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Pekerjaan</label>
                        <input type="text" name="pekerjaan" id="editPekerjaan" class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500" />
                    </div>
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Instansi / Tempat Kerja</label>
                        <input type="text" name="instansi" id="editInstansi" class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500" />
                    </div>
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Alamat Lengkap KTP</label>
                    <textarea name="alamat" id="editAlamat" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"></textarea>
                </div>
            </div>

            <!-- Section 3: Rekening Bank -->
            <div class="space-y-3 pt-2">
                <h4 class="font-bold text-blue-800 uppercase tracking-wider text-[11px] border-b border-gray-100 pb-1">
                    <i class="fas fa-university mr-1"></i> Rekening Bank Pembayaran
                </h4>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Nama Bank</label>
                        <input type="text" name="jenis_bank" id="editJenisBank" placeholder="BSI / BCA" class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500" />
                    </div>
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">No. Rekening</label>
                        <input type="text" name="no_rek" id="editNoRek" class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500" />
                    </div>
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Atas Nama Rekening</label>
                        <input type="text" name="atasnama_rekening" id="editAtasNamaRek" class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500" />
                    </div>
                </div>
            </div>

            <!-- Section 4: Re-Upload File Berkas -->
            <div class="space-y-3 pt-2">
                <h4 class="font-bold text-blue-800 uppercase tracking-wider text-[11px] border-b border-gray-100 pb-1">
                    <i class="fas fa-file-image mr-1"></i> Update Berkas Foto (Biarkan kosong jika tidak diubah)
                </h4>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Pas Foto Diri</label>
                        <input id="editFotoDiri" type="file" name="foto_diri" accept="image/*" class="w-full text-xs text-gray-500 file:mr-2 file:py-1 file:px-2 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700" />
                        <img id="previewEditFotoDiri" class="hidden mt-3 h-28 w-full object-cover rounded-xl border border-gray-200" alt="Preview Foto Diri" />
                    </div>
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Foto KTP</label>
                        <input id="editFotoKtp" type="file" name="foto_ktp" accept="image/*" class="w-full text-xs text-gray-500 file:mr-2 file:py-1 file:px-2 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700" />
                        <img id="previewEditFotoKtp" class="hidden mt-3 h-28 w-full object-cover rounded-xl border border-gray-200" alt="Preview Foto KTP" />
                    </div>
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Selfie KTP</label>
                        <input id="editFotoSelfie" type="file" name="foto_diri_ktp" accept="image/*" class="w-full text-xs text-gray-500 file:mr-2 file:py-1 file:px-2 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700" />
                        <img id="previewEditFotoSelfie" class="hidden mt-3 h-28 w-full object-cover rounded-xl border border-gray-200" alt="Preview Selfie KTP" />
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex space-x-3 pt-4 border-t border-gray-100">
                <button type="button" onclick="closeModal('editMemberModal')" class="flex-1 bg-gray-100 text-gray-600 py-2.5 rounded-xl hover:bg-gray-200 transition-colors font-bold cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="flex-1 bg-blue-600 text-white py-2.5 rounded-xl hover:bg-blue-700 transition-colors font-bold shadow-md shadow-blue-600/20 cursor-pointer">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL KONFIRMASI RESET PASSWORD -->
<div id="resetPasswordModal" class="modal fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50 hidden">
    <div class="bg-white p-6 rounded-xl shadow-xl max-w-md w-full mx-4">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Reset Password</h3>
        <p class="text-gray-600 text-sm mb-6" id="resetPasswordText">
            Apakah Anda yakin ingin mereset password anggota?
        </p>
        <div class="flex space-x-3">
            <button type="button" onclick="closeModal('resetPasswordModal')" class="flex-1 bg-gray-500 text-white py-2 rounded-md hover:bg-gray-600 transition-colors text-sm">
                Batal
            </button>
            <button type="button" onclick="confirmResetPassword()" class="flex-1 bg-orange-600 text-white py-2 rounded-md hover:bg-orange-700 transition-colors text-sm font-semibold" id="confirmResetBtn">
                Reset Password
            </button>
        </div>
    </div>
</div>

<!-- MODAL KONFIRMASI STATUS ANGGOTA -->
<div id="statusMemberModal" class="modal fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50 hidden">
    <div class="bg-white p-6 rounded-xl shadow-xl max-w-md w-full mx-4">
        <h3 class="text-xl font-bold text-gray-800 mb-4" id="statusMemberTitle">Ubah Status Anggota</h3>
        <p class="text-gray-600 text-sm mb-6" id="statusMemberText">
            Apakah Anda yakin ingin mengubah status anggota?
        </p>
        <div class="flex space-x-3">
            <button type="button" onclick="closeModal('statusMemberModal')" class="flex-1 bg-gray-500 text-white py-2 rounded-md hover:bg-gray-600 transition-colors text-sm">
                Batal
            </button>
            <button type="button" onclick="confirmToggleStatus()" class="flex-1 bg-orange-600 text-white py-2 rounded-md hover:bg-orange-700 transition-colors text-sm font-semibold" id="confirmStatusBtn">
                Ubah Status
            </button>
        </div>
    </div>
</div>

<!-- MODAL KONFIRMASI HAPUS ANGGOTA -->
<div id="deleteMemberModal" class="modal fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50 hidden">
    <div class="bg-white p-6 rounded-xl shadow-xl max-w-lg w-full mx-4">
        <h3 class="text-xl font-bold text-gray-800 mb-4" id="deleteMemberTitle">Hapus Anggota</h3>

        <div id="deleteMemberContent"></div>

        <div id="deleteLoading" class="hidden text-center py-8">
            <i class="fas fa-spinner fa-spin text-2xl text-blue-600 mb-4"></i>
            <p class="text-gray-600 text-sm">Memuat data anggota...</p>
        </div>

        <div class="mt-6 pt-4 border-t border-gray-200">
            <div class="flex space-x-3">
                <button type="button" onclick="closeModal('deleteMemberModal')"
                    class="flex-1 bg-gray-500 text-white py-2 rounded-md hover:bg-gray-600 transition-colors text-sm">
                    Batal
                </button>
                <div class="flex space-x-2 flex-1">
                    <button type="button" onclick="confirmDeleteMember(false)"
                        class="flex-1 bg-orange-600 text-white py-2 rounded-md hover:bg-orange-700 transition-colors text-sm font-semibold">
                        Nonaktifkan
                    </button>
                    <button type="button" onclick="confirmDeleteMember(true)"
                        class="flex-1 bg-red-600 text-white py-2 rounded-md hover:bg-red-700 transition-colors text-sm font-semibold">
                        Hapus Permanen
                    </button>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-3">
                <i class="fas fa-info-circle mr-1"></i>
                <strong>Nonaktifkan:</strong> Data tetap tersimpan.<br>
                <strong>Hapus Permanen:</strong> Data dihapus selamanya.
            </p>
        </div>
    </div>
</div>

<script>
    let currentUserId = null;
    let currentUserName = null;

    function resetPassword(userId, userName) {
        if (userId === null || userId === undefined || userId === 'null' || userId === 'undefined') {
            alert('Error: User ID tidak valid untuk ' + userName);
            return;
        }

        currentUserId = userId;
        currentUserName = userName;

        const resetText = document.getElementById('resetPasswordText');
        if (resetText) {
            resetText.innerHTML =
                `Apakah Anda yakin ingin mereset password untuk:<br>
             <strong>${userName}</strong><br><br>
             User ID: <strong>${userId}</strong><br>
             Password akan dikembalikan ke: <strong>123</strong>`;
        }

        openModal('resetPasswordModal');
    }

    function confirmResetPassword() {
        if (!currentUserId) {
            alert('Error: User ID tidak ditemukan di frontend');
            return;
        }

        const button = document.getElementById('confirmResetBtn');
        const originalText = button.innerHTML;

        button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
        button.disabled = true;

        const formData = new FormData();
        formData.append('user_id', currentUserId);
        formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

        fetch('<?= base_url('admin/reset-password') ?>', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert('✅ Password berhasil direset ke "123"');
                    closeModal('resetPasswordModal');
                } else {
                    alert('❌ ' + (data.message || 'Gagal mereset password'));
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                alert('❌ Terjadi kesalahan jaringan');
            })
            .finally(() => {
                button.innerHTML = originalText;
                button.disabled = false;
            });
    }

    let currentDeleteMemberId = null;

    function showDeleteModal(memberId) {
        currentDeleteMemberId = memberId;

        const contentDiv = document.getElementById('deleteMemberContent');
        const loadingDiv = document.getElementById('deleteLoading');

        contentDiv.classList.add('hidden');
        loadingDiv.classList.remove('hidden');

        const detailsUrl = '<?= base_url("admin/get-member-details/") ?>' + memberId;

        fetch(detailsUrl, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                loadingDiv.classList.add('hidden');
                contentDiv.classList.remove('hidden');

                if (data.status === 'success' && data.data) {
                    const anggota = data.data.anggota;
                    const summary = data.data.summary;
                    const totalData = data.data.total_data_terkait || 0;

                    let summaryHtml = '';
                    if (totalData > 0) {
                        let detailItems = [];
                        if (summary.simpanan_pokok > 0) detailItems.push(`${summary.simpanan_pokok} simpanan pokok`);
                        if (summary.simpanan_wajib > 0) detailItems.push(`${summary.simpanan_wajib} simpanan wajib`);
                        if (summary.simpanan_sukarela > 0) detailItems.push(`${summary.simpanan_sukarela} simpanan sukarela`);
                        if (summary.pembiayaan_aktif > 0) detailItems.push(`${summary.pembiayaan_aktif} pembiayaan aktif`);
                        if (summary.pembayaran_pending > 0) detailItems.push(`${summary.pembayaran_pending} pembayaran pending`);

                        summaryHtml = `
                    <div class="bg-yellow-50 border border-yellow-200 rounded-md p-3 mb-4">
                        <p class="text-xs text-yellow-800 font-semibold mb-1">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            Anggota ini memiliki ${totalData} data terkait
                        </p>
                        <ul class="text-xs text-yellow-700 ml-4 list-disc">
                            ${detailItems.map(item => `<li>${item}</li>`).join('')}
                        </ul>
                    </div>
                `;
                    }

                    contentDiv.innerHTML = `
                ${summaryHtml}
                <div class="mb-4">
                    <p class="text-xs font-semibold text-gray-700 mb-2">Detail Anggota:</p>
                    <div class="bg-gray-50 p-3 rounded-md border border-gray-200">
                        <table class="text-xs w-full">
                            <tr>
                                <td class="py-1 text-gray-600 font-medium w-1/3">Nama</td>
                                <td class="py-1"><strong>${anggota.nama}</strong></td>
                            </tr>
                            <tr>
                                <td class="py-1 text-gray-600 font-medium">No. Anggota</td>
                                <td class="py-1">${anggota.nomor_anggota}</td>
                            </tr>
                            <tr>
                                <td class="py-1 text-gray-600 font-medium">Email</td>
                                <td class="py-1">${anggota.email}</td>
                            </tr>
                            <tr>
                                <td class="py-1 text-gray-600 font-medium">Status</td>
                                <td class="py-1">
                                    <span class="px-2 py-0.5 text-[10px] rounded-full ${anggota.status === 'aktif' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'}">
                                        ${anggota.status}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            `;
                } else {
                    contentDiv.innerHTML = `
                <div class="bg-red-50 border border-red-200 rounded-md p-3">
                    <p class="text-xs text-red-700">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        ${data.message || 'Gagal memuat data'}
                    </p>
                </div>
            `;
                }
            })
            .catch(error => {
                loadingDiv.classList.add('hidden');
                contentDiv.classList.remove('hidden');
                contentDiv.innerHTML = `
            <div class="bg-red-50 border border-red-200 rounded-md p-3">
                <p class="text-xs text-red-700 font-semibold">Error: ${error.message}</p>
            </div>
        `;
            });

        openModal('deleteMemberModal');
    }

    function confirmDeleteMember(hardDelete = false) {
        if (!currentDeleteMemberId) return;

        const confirmText = hardDelete ?
            'Apakah Anda YAKIN ingin menghapus PERMANEN?' :
            'Apakah Anda yakin ingin menonaktifkan?';

        if (!confirm(confirmText)) return;

        const csrfToken = document.querySelector('input[name="<?= csrf_token() ?>"]')?.value || '<?= csrf_hash() ?>';

        const formData = new FormData();
        formData.append('member_id', currentDeleteMemberId);
        formData.append('hard_delete', hardDelete);
        formData.append('<?= csrf_token() ?>', csrfToken);

        fetch('<?= base_url("admin/delete-anggota") ?>', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert('✅ ' + data.message);
                    location.reload();
                } else {
                    alert('❌ ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('❌ Terjadi kesalahan jaringan');
            });
    }

    let currentMemberId = null;
    let currentMemberName = null;
    let currentMemberStatus = null;

    function toggleMemberStatus(memberId, memberName, currentStatus) {
        currentMemberId = memberId;
        currentMemberName = memberName;
        currentMemberStatus = currentStatus.toLowerCase();

        const isCurrentlyActive = currentMemberStatus === 'aktif';
        const newStatus = isCurrentlyActive ? 'nonaktif' : 'aktif';

        const statusTitle = document.getElementById('statusMemberTitle');
        const statusText = document.getElementById('statusMemberText');
        const confirmBtn = document.getElementById('confirmStatusBtn');

        if (statusTitle && statusText && confirmBtn) {
            statusTitle.textContent = isCurrentlyActive ? 'Nonaktifkan Anggota' : 'Aktifkan Anggota';
            statusText.innerHTML =
                `Apakah Anda yakin ingin <strong>${isCurrentlyActive ? 'menonaktifkan' : 'mengaktifkan'}</strong> anggota:<br>
             <strong>${memberName}</strong><br><br>
             ID Anggota: <strong>${memberId}</strong><br>
             Status saat ini: <strong>${currentStatus}</strong><br>
             Status akan diubah menjadi: <strong>${newStatus}</strong>`;

            confirmBtn.textContent = isCurrentlyActive ? 'Nonaktifkan' : 'Aktifkan';
            confirmBtn.className = isCurrentlyActive ?
                'flex-1 bg-orange-600 text-white py-2 rounded-md hover:bg-orange-700 transition-colors text-sm font-semibold' :
                'flex-1 bg-emerald-600 text-white py-2 rounded-md hover:bg-emerald-700 transition-colors text-sm font-semibold';
        }

        openModal('statusMemberModal');
    }

    function confirmToggleStatus() {
        if (!currentMemberId) {
            alert('Error: Member ID tidak ditemukan');
            return;
        }

        const button = document.getElementById('confirmStatusBtn');
        const originalText = button.innerHTML;

        button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
        button.disabled = true;

        const formData = new FormData();
        formData.append('member_id', currentMemberId);
        formData.append('current_status', currentMemberStatus);
        formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

        fetch('<?= base_url('admin/toggle-member-status') ?>', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert('✅ Status anggota berhasil diubah!');
                    closeModal('statusMemberModal');
                    location.reload();
                } else {
                    alert('❌ ' + (data.message || 'Gagal mengubah status anggota'));
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                alert('❌ Terjadi kesalahan jaringan');
            })
            .finally(() => {
                button.innerHTML = originalText;
                button.disabled = false;
            });
    }

    // Function untuk Membuka dan Mengisi Data ke Form Edit
    function openEditMemberModal(button) {
        const id = button.getAttribute('data-id');
        if (!id) return;

        // Set URL Action Form secara dinamis
        const form = document.getElementById('editMemberForm');
        form.action = '<?= base_url('admin/update-anggota') ?>/' + id;
        document.getElementById('editMemberId').value = id;
        document.getElementById('editMemberTitle').textContent = 'Edit Anggota';

        // Ambil data lengkap via Fetch/AJAX agar semua input terisi sempurna
        fetch('<?= base_url("admin/get-anggota-detail/") ?>' + id, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(res => {
                if (res.status === 'success' && res.data) {
                    const d = res.data;
                    document.getElementById('editNamaLengkap').value = d.nama_lengkap || '';
                    document.getElementById('editNoKtp').value = d.no_ktp || '';
                    document.getElementById('editStatus').value = (d.status || 'aktif').toLowerCase();
                    document.getElementById('editTanggalDaftar').value = d.tanggal_daftar || '';
                    document.getElementById('editJenisKelamin').value = d.jenis_kelamin || 'L';
                    document.getElementById('editNoHp').value = d.no_hp || '';
                    document.getElementById('editPekerjaan').value = d.pekerjaan || '';
                    document.getElementById('editInstansi').value = d.instansi || '';
                    document.getElementById('editAlamat').value = d.alamat || '';
                    document.getElementById('editJenisBank').value = d.jenis_bank || '';
                    document.getElementById('editNoRek').value = d.no_rek || '';
                    document.getElementById('editAtasNamaRek').value = d.atasnama_rekening || '';

                    clearMemberPreview(['previewEditFotoDiri', 'previewEditFotoKtp', 'previewEditFotoSelfie']);
                    openModal('editMemberModal');
                } else {
                    alert('Gagal mengambil detail data anggota');
                }
            })
            .catch(err => {
                console.error('Error fetching detail:', err);
                // Fallback jika fetch error: isi data dasar dari attribute button
                document.getElementById('editNamaLengkap').value = button.getAttribute('data-name') || '';
                document.getElementById('editNoKtp').value = button.getAttribute('data-ktp') || '';
                document.getElementById('editStatus').value = (button.getAttribute('data-status') || 'aktif').toLowerCase();
                document.getElementById('editTanggalDaftar').value = button.getAttribute('data-tanggal') || '';
                clearMemberPreview(['previewEditFotoDiri', 'previewEditFotoKtp', 'previewEditFotoSelfie']);
                openModal('editMemberModal');
            });
    }

    // Function Submit AJAX Form Edit Anggota
    document.getElementById('editMemberForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;

        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
        submitBtn.disabled = true;

        fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    alert('✅ ' + data.message);
                    closeModal('editMemberModal');
                    location.reload();
                } else {
                    alert('❌ ' + (data.message || 'Gagal menyimpan perubahan.'));
                }
            })
            .catch(err => {
                console.error('Edit error:', err);
                alert('❌ Terjadi kesalahan jaringan saat menyimpan.');
            })
            .finally(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
    });

    function openEditMemberModalFromData(data) {
        const form = document.getElementById('editMemberForm');
        if (!form) return;

        document.getElementById('editMemberId').value = data.id || '';
        document.getElementById('editNamaLengkap').value = data.name || '';
        document.getElementById('editNoKtp').value = data.ktp || '';
        document.getElementById('editStatus').value = data.status || '';
        document.getElementById('editTanggalDaftar').value = data.tanggal || '';
        document.getElementById('editMemberTitle').textContent = 'Edit Anggota';
        form.action = '<?= base_url('admin/update-anggota') ?>/' + (data.id || '');
        clearMemberPreview(['previewEditFotoDiri', 'previewEditFotoKtp', 'previewEditFotoSelfie']);

        openModal('editMemberModal');
    }

    function openModal(id) {
        const el = document.getElementById(id);
        if (el) {
            el.classList.remove("hidden");
            el.classList.add("flex");
        }
    }

    function closeModal(id) {
        const el = document.getElementById(id);
        if (el) {
            el.classList.add("hidden");
            el.classList.remove("flex");
        }
    }

    function clearMemberPreview(previewIds) {
        previewIds.forEach((id) => {
            const img = document.getElementById(id);
            if (img) {
                img.src = '';
                img.classList.add('hidden');
            }
        });
    }

    function openAddMemberModal() {
        const form = document.getElementById('formMember');
        if (!form) return;

        form.reset();
        clearMemberPreview(['previewMemberFotoDiri', 'previewMemberFotoKtp', 'previewMemberFotoSelfie']);
        openModal('memberModal');
    }

    // Helper Function Toast Alert (Pengganti alert localhost)
    function showToast(message, type = 'success') {
        const toast = document.getElementById('toastNotification');
        const toastIcon = document.getElementById('toastIcon');
        const toastTitle = document.getElementById('toastTitle');
        const toastMsg = document.getElementById('toastMessage');

        if (!toast) {
            // Fallback jika container toast belum dipasang
            console.log(`[${type.toUpperCase()}] ${message}`);
            return;
        }

        if (type === 'success') {
            toast.className = 'fixed top-5 right-5 z-50 max-w-xs w-full bg-white rounded-xl shadow-2xl border border-emerald-200 p-4 transition-all duration-300 transform translate-y-0';
            toastIcon.innerHTML = '<i class="fas fa-check-circle text-emerald-500 text-xl"></i>';
            toastTitle.textContent = 'Berhasil!';
        } else {
            toast.className = 'fixed top-5 right-5 z-50 max-w-xs w-full bg-white rounded-xl shadow-2xl border border-rose-200 p-4 transition-all duration-300 transform translate-y-0';
            toastIcon.innerHTML = '<i class="fas fa-exclamation-circle text-rose-500 text-xl"></i>';
            toastTitle.textContent = 'Gagal!';
        }

        toastMsg.textContent = message;
        toast.classList.remove('hidden');

        // Otomatis sembunyikan dalam 4 detik
        setTimeout(() => {
            hideToast();
        }, 4000);
    }

    function hideToast() {
        const toast = document.getElementById('toastNotification');
        if (toast) toast.classList.add('hidden');
    }

    // Handler Submit Form Tambah Anggota (AJAX)
    document.getElementById('formMember').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;

        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
        submitBtn.disabled = true;

        // Menembak URL endpoint saveMember
        fetch('<?= base_url('admin/dashboard_admin/members/save') ?>', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => {
                if (!res.ok) {
                    throw new Error('Terjadi kesalahan koneksi server (' + res.status + ')');
                }
                return res.json();
            })
            .then(data => {
                if (data.status === 'success') {
                    showToast(data.message || 'Anggota baru berhasil ditambahkan!', 'success');
                    closeModal('memberModal');
                    setTimeout(() => {
                        location.reload();
                    }, 1200);
                } else {
                    // Tampilkan pesan error spesifik dari Controller
                    showToast(data.message || 'Gagal menyimpan data anggota.', 'error');
                }
            })
            .catch(err => {
                console.error('Error Save Member:', err);
                showToast('Terjadi kesalahan jaringan atau server.', 'error');
            })
            .finally(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
    });

    // Live search anggota
    const searchInput = document.getElementById('searchInput');
    const anggotaTableBody = document.getElementById('anggotaTableBody');
    let searchTimeout;

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value.trim();
            searchTimeout = setTimeout(() => {
                if (query.length === 0) {
                    location.reload();
                    return;
                }

                fetch('/admin/search-anggota?q=' + encodeURIComponent(query))
                    .then(res => res.json())
                    .then(data => {
                        anggotaTableBody.innerHTML = '';
                        if (data.length === 0) {
                            anggotaTableBody.innerHTML = `<tr><td colspan="6" class="text-center py-4 text-gray-500">Tidak ada data ditemukan</td></tr>`;
                            return;
                        }
                        data.forEach(item => {
                            let badge = (item.status.toLowerCase() === 'aktif') ?
                                `<span class='px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800'>${item.status}</span>` :
                                `<span class='px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800'>${item.status}</span>`;

                            const userId = item.id_user;
                            const anggotaId = item.id_anggota;
                            const canReset = userId !== null && userId !== undefined;
                            const resetClass = canReset ? 'text-amber-600 hover:text-amber-800 hover:bg-amber-50 p-1.5 rounded transition-colors' : 'text-gray-300 cursor-not-allowed p-1.5';
                            const resetTitle = canReset ? 'Reset Password' : 'User tidak ditemukan';

                            anggotaTableBody.innerHTML += `
                            <tr class='hover:bg-gray-50 transition duration-150 cursor-pointer' onclick="window.location.href='${item.urlDetail}'">
                                <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-900'>${anggotaId}</td>
                                <td class='px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900'>${item.nama_lengkap}</td>
                                <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-600'>${item.no_ktp}</td>
                                <td class='px-6 py-4 whitespace-nowrap'>${badge}</td>
                                <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-600'>${item.tanggal_daftar}</td>
                                <td class='px-6 py-4 whitespace-nowrap text-center text-sm font-medium space-x-1'>
                                    <button type="button" onclick="event.stopPropagation(); openEditMemberModalFromData({id:${anggotaId}, name:'${item.nama_lengkap.replace(/'/g, "\\'")}', ktp:'${(item.no_ktp || '').replace(/'/g, "\\'")}', status:'${(item.status || '').replace(/'/g, "\\'")}', tanggal:'${(item.tanggal_daftar || '').replace(/'/g, "\\'")}'});" class='text-blue-600 hover:text-blue-900 p-1.5 hover:bg-blue-50 rounded transition-colors' title="Edit">
                                        <i class='fas fa-edit'></i>
                                    </button>
                                    <button onclick="event.stopPropagation(); window.location.href='${item.urlDetail}'" class='text-emerald-600 hover:text-emerald-900 p-1.5 hover:bg-emerald-50 rounded transition-colors' title="Detail">
                                        <i class='fas fa-eye'></i>
                                    </button>
                                    ${canReset ? 
                                        `<button onclick="event.stopPropagation(); resetPassword(${userId}, '${item.nama_lengkap.replace(/'/g, "\\'")}')" class='${resetClass}' title="${resetTitle}">
                                            <i class='fas fa-key'></i>
                                        </button>` :
                                        `<button class='${resetClass}' title="${resetTitle}" disabled>
                                            <i class='fas fa-key'></i>
                                        </button>`
                                    }
                                </td>
                            </tr>
                        `;
                        });
                    })
                    .catch(err => {
                        console.error('Search error:', err);
                        anggotaTableBody.innerHTML = `<tr><td colspan="6" class="text-center py-4 text-gray-500">Error saat mencari data</td></tr>`;
                    });
            }, 500);
        });
    }
</script>