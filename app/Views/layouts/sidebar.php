<aside class="w-64 bg-gradient-to-b from-emerald-900 via-emerald-800 to-teal-900 min-h-screen text-slate-100 flex flex-col justify-between select-none shadow-xl border-r border-emerald-700/50 shrink-0">
  <div>
   

    <!-- Navigation Menu -->
    <nav class="mt-6">
      <div class="px-4 mb-2">
        <p class="text-[10px] font-bold uppercase tracking-wider text-emerald-300/80">Menu Utama</p>
      </div>
      <ul class="space-y-1 px-2.5">

        <?php 
          // Helper fungsi serbaguna yang memeriksa multiple alias path agar menu aktif 100% konsisten
          function getNavStyle($paths) {
              if (!is_array($paths)) {
                  $paths = [$paths];
              }
              
              $isActive = false;
              foreach ($paths as $path) {
                  if (url_is($path) || url_is($path . '/*')) {
                      $isActive = true;
                      break;
                  }
              }

              if ($isActive) {
                  return [
                      'link' => 'bg-gradient-to-r from-emerald-700/90 via-emerald-700/60 to-transparent text-white font-bold shadow-md border-r border-amber-400/30',
                      'icon' => 'text-amber-300 drop-shadow-[0_0_8px_rgba(251,191,36,0.7)]',
                      'bar'  => 'w-1 h-6 bg-gradient-to-b from-amber-300 via-yellow-400 to-amber-500 rounded-r-full absolute left-0 shadow-[0_0_10px_rgba(251,191,36,0.9)] z-10'
                  ];
              }
              return [
                  'link' => 'text-emerald-100/80 hover:text-white hover:bg-emerald-700/40',
                  'icon' => 'text-emerald-300/70 group-hover:text-amber-300 transition-colors',
                  'bar'  => 'w-0 h-0 hidden'
              ];
          }
        ?>

        <!-- 1. Dashboard Utama -->
        <?php $style = getNavStyle(['admin', 'admin/dashboard']); ?>
        <li class="relative flex items-center h-10">
          <div class="<?= $style['bar'] ?>"></div>
          <a href="<?= base_url('admin') ?>" class="group sidebar-item w-full flex items-center space-x-3 py-2 px-3 rounded-xl transition-all duration-150 <?= $style['link'] ?>">
            <i class="fas fa-tachometer-alt text-base w-5 text-center shrink-0 <?= $style['icon'] ?>"></i>
            <span class="text-sm whitespace-nowrap">Dashboard Utama</span>
          </a>
        </li>

        <!-- 2. Manajemen Anggota -->
        <?php $style = getNavStyle(['admin/dashboard_admin/members', 'admin/members']); ?>
        <li class="relative flex items-center h-10">
          <div class="<?= $style['bar'] ?>"></div>
          <a href="<?= base_url('admin/dashboard_admin/members') ?>" class="group sidebar-item w-full flex items-center space-x-3 py-2 px-3 rounded-xl transition-all duration-150 <?= $style['link'] ?>">
            <i class="fas fa-users text-base w-5 text-center shrink-0 <?= $style['icon'] ?>"></i>
            <span class="text-sm whitespace-nowrap">Manajemen Anggota</span>
          </a>
        </li>

        <!-- 3. Manajemen Simpanan -->
        <?php $style = getNavStyle(['admin/dashboard_admin/savings', 'admin/savings']); ?>
        <li class="relative flex items-center h-10">
          <div class="<?= $style['bar'] ?>"></div>
          <a href="<?= base_url('admin/dashboard_admin/savings') ?>" class="group sidebar-item w-full flex items-center space-x-3 py-2 px-3 rounded-xl transition-all duration-150 <?= $style['link'] ?>">
            <i class="fas fa-coins text-base w-5 text-center shrink-0 <?= $style['icon'] ?>"></i>
            <span class="text-sm whitespace-nowrap">Manajemen Simpanan</span>
          </a>
        </li>

        <!-- 4. Manajemen Pinjaman -->
        <?php $style = getNavStyle(['admin/dashboard_admin/financing', 'admin/financing']); ?>
        <li class="relative flex items-center h-10">
          <div class="<?= $style['bar'] ?>"></div>
          <a href="<?= base_url('admin/dashboard_admin/financing') ?>" class="group sidebar-item w-full flex items-center space-x-3 py-2 px-3 rounded-xl transition-all duration-150 <?= $style['link'] ?>">
            <i class="fas fa-hand-holding-usd text-base w-5 text-center shrink-0 <?= $style['icon'] ?>"></i>
            <span class="text-sm whitespace-nowrap">Manajemen Pinjaman</span>
          </a>
        </li>

        <!-- 5. Manajemen Angsuran -->
        <?php $style = getNavStyle(['admin/dashboard_admin/installments', 'admin/installments']); ?>
        <li class="relative flex items-center h-10">
          <div class="<?= $style['bar'] ?>"></div>
          <a href="<?= base_url('admin/dashboard_admin/installments') ?>" class="group sidebar-item w-full flex items-center space-x-3 py-2 px-3 rounded-xl transition-all duration-150 <?= $style['link'] ?>">
            <i class="fas fa-calendar-check text-base w-5 text-center shrink-0 <?= $style['icon'] ?>"></i>
            <span class="text-sm whitespace-nowrap">Manajemen Angsuran</span>
          </a>
        </li>

        <!-- 6. Transaksi Umum -->
        <?php $style = getNavStyle(['admin/dashboard_admin/transactions', 'admin/transactions']); ?>
        <li class="relative flex items-center h-10">
          <div class="<?= $style['bar'] ?>"></div>
          <a href="<?= base_url('admin/dashboard_admin/transactions') ?>" class="group sidebar-item w-full flex items-center space-x-3 py-2 px-3 rounded-xl transition-all duration-150 <?= $style['link'] ?>">
            <i class="fas fa-exchange-alt text-base w-5 text-center shrink-0 <?= $style['icon'] ?>"></i>
            <span class="text-sm whitespace-nowrap">Transaksi Umum</span>
          </a>
        </li>

        <!-- Divider Kategori -->
        <div class="pt-4 pb-1 px-4">
          <p class="text-[10px] font-bold uppercase tracking-wider text-emerald-300/80">Laporan & Sistem</p>
        </div>

        <!-- 7. Laporan & Analisis -->
        <?php $style = getNavStyle(['admin/dashboard_admin/reports', 'admin/reports']); ?>
        <li class="relative flex items-center h-10">
          <div class="<?= $style['bar'] ?>"></div>
          <a href="<?= base_url('admin/dashboard_admin/reports') ?>" class="group sidebar-item w-full flex items-center space-x-3 py-2 px-3 rounded-xl transition-all duration-150 <?= $style['link'] ?>">
            <i class="fas fa-chart-bar text-base w-5 text-center shrink-0 <?= $style['icon'] ?>"></i>
            <span class="text-sm whitespace-nowrap">Laporan & Analisis</span>
          </a>
        </li>
        
        <!-- 8. Pengaturan -->
        <?php $style = getNavStyle(['admin/dashboard_admin/settings', 'admin/settings']); ?>
        <li class="relative flex items-center h-10">
          <div class="<?= $style['bar'] ?>"></div>
          <a href="<?= base_url('admin/dashboard_admin/settings') ?>" class="group sidebar-item w-full flex items-center space-x-3 py-2 px-3 rounded-xl transition-all duration-150 <?= $style['link'] ?>">
            <i class="fas fa-cog text-base w-5 text-center shrink-0 <?= $style['icon'] ?>"></i>
            <span class="text-sm whitespace-nowrap">Pengaturan</span>
          </a>
        </li>

        <!-- 9. Fitur Tambahan -->
        <?php $style = getNavStyle(['admin/dashboard_admin/extras', 'admin/extras']); ?>
        <li class="relative flex items-center h-10">
          <div class="<?= $style['bar'] ?>"></div>
          <a href="<?= base_url('admin/dashboard_admin/extras') ?>" class="group sidebar-item w-full flex items-center space-x-3 py-2 px-3 rounded-xl transition-all duration-150 <?= $style['link'] ?>">
            <i class="fas fa-plus-circle text-base w-5 text-center shrink-0 <?= $style['icon'] ?>"></i>
            <span class="text-sm whitespace-nowrap">Fitur Tambahan</span>
          </a>
        </li>

      </ul>
    </nav>
  </div>

  <!-- Bottom Admin Profile Info Card -->
  <div class="p-3 border-t border-emerald-700/60 m-3 rounded-xl bg-emerald-950/40 backdrop-blur-sm">
    <div class="flex items-center space-x-3">
      <div class="w-8 h-8 rounded-full bg-emerald-800 border-2 border-amber-400 flex items-center justify-center text-amber-300 font-bold text-xs shadow-sm shrink-0">
        A
      </div>
      <div class="flex-1 min-w-0">
        <p class="text-xs font-bold text-white truncate">Administrator</p>
        <p class="text-[10px] text-emerald-300/80 truncate">admin@koperasi.com</p>
      </div>
    </div>
  </div>
</aside>