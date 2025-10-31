<aside class="w-64 bg-gradient-to-b from-emerald-800 to-emerald-900 min-h-screen text-white">
  <nav class="mt-8">
    <ul class="space-y-2 px-4">
      <li>
        <a href="<?= base_url('admin') ?>" class="sidebar-item flex items-center space-x-3 py-3 px-4 rounded-lg">
          <i class="fas fa-tachometer-alt"></i>
          <span>Dashboard Utama</span>
        </a>
      </li>

      <li>
        <a href="<?= base_url('admin/dashboard_admin/members') ?>" class="sidebar-item flex items-center space-x-3 py-3 px-4 rounded-lg">
          <i class="fas fa-users"></i>
          <span>Manajemen Anggota</span>
        </a>
      </li>

      <li>
        <a href="<?= base_url('admin/dashboard_admin/savings') ?>" class="sidebar-item flex items-center space-x-3 py-3 px-4 rounded-lg">
          <i class="fas fa-coins"></i>
          <span>Manajemen Simpanan</span>
        </a>
      </li>

      <li>
        <a href="<?= base_url('admin/dashboard_admin/financing') ?>" class="sidebar-item flex items-center space-x-3 py-3 px-4 rounded-lg">
          <i class="fas fa-hand-holding-usd"></i>
          <span>Manajemen Pinjaman</span>
        </a>
      </li>

      <!-- Menu Manajemen Angsuran -->
      <li>
        <a href="<?= base_url('admin/dashboard_admin/installments') ?>" class="sidebar-item flex items-center space-x-3 py-3 px-4 rounded-lg bg-emerald-700">
          <i class="fas fa-calendar-check"></i>
          <span>Manajemen Angsuran</span>
        </a>
      </li>

      <li>
        <a href="<?= base_url('admin/dashboard_admin/transactions') ?>" class="sidebar-item flex items-center space-x-3 py-3 px-4 rounded-lg">
          <i class="fas fa-exchange-alt"></i>
          <span>Transaksi Umum</span>
        </a>
      </li>

      <li>
        <a href="<?= base_url('admin/dashboard_admin/reports') ?>" class="sidebar-item flex items-center space-x-3 py-3 px-4 rounded-lg">
          <i class="fas fa-chart-bar"></i>
          <span>Laporan & Analisis</span>
        </a>
      </li>
      
      <li>
        <a href="<?= base_url('admin/dashboard_admin/settings') ?>" class="sidebar-item flex items-center space-x-3 py-3 px-4 rounded-lg">
          <i class="fas fa-cog"></i>
          <span>Pengaturan</span>
        </a>
      </li>
      <li><a href="<?= base_url('admin/dashboard_admin/extras') ?>" class="sidebar-item flex items-center space-x-3 py-3 px-4 rounded-lg"><i class="fas fa-plus-circle"></i><span>Fitur Tambahan</span></a></li>
    </ul>
  </nav>
</aside>