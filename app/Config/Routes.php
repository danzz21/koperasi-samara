<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('login/admin', 'Auth::login');
$routes->get('login', 'Auth::login');
$routes->post('auth/doLogin', 'Auth::doLogin');
$routes->get('logout', 'Auth::logout');

// Forgot Password Routes
$routes->get('auth/forgotPassword', 'Auth::forgotPassword');
$routes->post('auth/processForgotPassword', 'Auth::processForgotPassword');
$routes->get('auth/resetSuccess', 'Auth::resetSuccess');

// Register Routes
$routes->get('register', 'RegisterController::index');
$routes->post('register/store', 'RegisterController::store');

// Default route
$routes->get('/', 'Auth::login');

// ===========================
// ROUTE UNTUK ANGGOTA
// ===========================
$routes->group('anggota', ['filter' => 'role:anggota'], function ($routes) {
    $routes->get('dashboard', 'Dashboard::index');
    $routes->get('simpanan', 'Simpanan::index');
    $routes->get('pinjaman', 'Pinjaman::index');
    $routes->get('profil', 'Profil::index');
    $routes->post('profil/updateFoto', 'Profil::updateFoto');
$routes->get('riwayat-cicilan', 'Cicilan::riwayatCicilan');
    // Simpanan jenis
    $routes->get('sim_pokok', 'Sim_pokok::index');
    $routes->get('sim_wajib', 'Sim_wajib::index');
    $routes->get('sim_sukarela', 'Sim_sukarela::index');
    $routes->post('simpanan/sukarela/store', 'SimpananSukarela::store');

    $routes->get('cicilan', 'Cicilan::index');
    $routes->post('cicilan/bayar', 'Cicilan::bayarCicilan');

    $routes->get('profil/edit', 'Profil::edit');
    $routes->post('profil/update', 'Profil::update');

    $routes->get('profil/cetak-kartu', 'Profil::cetakKartu');
    $routes->post('simpanan/setTenor', 'Simpanan::setTenor');
    $routes->post('pinjaman/setTenor', 'Pinjaman::setTenor');
    $routes->post('cicilan/setTenor', 'Cicilan::setTenor');

    // Pinjaman jenis
    $routes->get('pin_alqordh', 'Pin_alqordh::index');
    $routes->get('pin_murobahah', 'Pin_murobahah::index');
    $routes->get('pin_mudhorobah', 'Pin_mudhorobah::index');
    $routes->post('ajukan-pinjaman', 'Pinjaman::ajukan');
    
    // ===== ROUTES BARU UNTUK VALIDASI NO REKENING =====
    $routes->post('update-rekening', 'Pinjaman::updateRekening');
    $routes->get('check-no-rekening', 'Pinjaman::checkNoRekening');
    $routes->get('validate-pinjaman', 'Pinjaman::validateBeforeSubmit');
    $routes->get('active-loan', 'Pinjaman::getActiveLoan');
    
    // ===== ROUTES UNTUK SIMPANAN (jika diperlukan) =====
    $routes->post('simpanan/input', 'Simpanan::inputSimpanan');
    $routes->get('simpanan/list', 'Simpanan::getSimpananList');
    $routes->get('search-anggota', 'Simpanan::searchAnggota');
    $routes->get('check-simpanan-pokok/(:num)', 'Simpanan::checkSimpananPokok/$1');
});
// Atau jika ingin lebih spesifik:
$routes->post('anggota/simpanan/pokok/store', 'Simpanan::storePokok');
$routes->post('anggota/simpanan/sukarela/store', 'Simpanan::storeSukarela');
$routes->post('anggota/simpanan/setTenor', 'Simpanan::setTenor');
$routes->get('anggota/simpanan', 'Simpanan::index');

// ===========================
// ROUTE UNTUK ADMIN
// ===========================
$routes->group('admin', ['filter' => 'role:admin'], function ($routes) {
    $routes->get('/', 'AdminDashboard::index');
    $routes->get('dashboard', 'AdminDashboard::index');
    $routes->get('dashboard_admin/members', 'AdminDashboard::members');
    $routes->get('search-anggota', 'AdminDashboard::searchAnggota');
    $routes->get('dashboard_admin/savings', 'AdminDashboard::savings');
    $routes->get('dashboard_admin/financing', 'AdminDashboard::financing');
    $routes->get('dashboard_admin/transactions', 'AdminDashboard::transactions');
    $routes->get('dashboard_admin/reports', 'AdminDashboard::reports');
    $routes->get('dashboard_admin/settings', 'AdminDashboard::settings');
    $routes->get('dashboard_admin/extras', 'AdminDashboard::extras');
    $routes->post('dashboard_admin/members/save', 'AdminDashboard::saveMember');
    $routes->get('detail-anggota/(:num)', 'AdminDashboard::detailAnggota/$1');

    // ✅ PERBAIKI ROUTES MANAJEMEN ANGSURAN - GUNAKAN DASHBOARD_ADMIN
    $routes->get('dashboard_admin/installments', 'AdminDashboard::installments');
    $routes->post('dashboard_admin/angsuran/bayar', 'AdminDashboard::bayarAngsuran');
    $routes->get('dashboard_admin/angsuran/detail', 'AdminDashboard::getDetailAngsuran');

    // Simpanan
    $routes->get('getSimpananList', 'AdminDashboard::getSimpananList');
    $routes->post('inputSimpanan', 'AdminDashboard::inputSimpanan');
    
    // Members
    $routes->get('pending-members', 'AdminDashboard::pendingMembers');
    $routes->get('verify/(:num)', 'AdminDashboard::verify/$1');
    $routes->get('reject/(:num)', 'AdminDashboard::reject/$1');
    
    // Pembiayaan
    $routes->post('savePembiayaan', 'AdminDashboard::savePembiayaan');
    $routes->post('approvePembiayaan', 'AdminDashboard::approvePembiayaan');
    $routes->post('rejectPembiayaan', 'AdminDashboard::rejectPembiayaan');

    // Transaksi Umum
    $routes->get('transactions', 'AdminDashboard::transactions');
    $routes->post('saveTransaksi', 'AdminDashboard::saveTransaksi');

    $routes->get('pembayaran-pending', 'AdminDashboard::pembayaranPending');
    $routes->post('pembayaran/verifikasi/(:num)', 'AdminDashboard::verifikasiPembayaran/$1');
    $routes->post('pembayaran/tolak/(:num)', 'AdminDashboard::tolakPembayaran/$1');
    
    // Pinjaman
    $routes->get('pending-pinjaman', 'AdminDashboard::pendingLoans');
    $routes->get('pinjaman/verifikasi/(:segment)/(:num)', 'AdminDashboard::verifikasiPinjaman/$1/$2');
    $routes->get('pinjaman/tolak/(:segment)/(:num)', 'AdminDashboard::tolakPinjaman/$1/$2');

    // Simpanan Sukarela
    $routes->get('pending-sukarela', 'AdminDashboard::pendingSukarela');
    $routes->get('approve-sukarela/(:num)', 'AdminDashboard::approveSukarela/$1');
    $routes->get('reject-sukarela/(:num)', 'AdminDashboard::rejectSukarela/$1');

    // Settings
    $routes->get('/admin/settings', 'AdminDashboard::settings');

    // Reset Password
    $routes->post('reset-password', 'AdminDashboard::resetPassword');

     $routes->post('toggle-member-status', 'AdminDashboard::toggleMemberStatus');
});

$routes->get('admindashboard/getAdmins', 'AdminDashboard::getAdmins');
$routes->get('admindashboard/getAdmin/(:num)', 'AdminDashboard::getAdmin/$1');
$routes->post('admindashboard/saveAdmin', 'AdminDashboard::saveAdmin');
$routes->delete('admindashboard/deleteAdmin/(:num)', 'AdminDashboard::deleteAdmin/$1');

$routes->post('/admindashboard/saveAkad', 'AdminDashboard::saveAkad');
$routes->get('/admindashboard/getAkadSettings', 'AdminDashboard::getAkadSettings');

$routes->get('extras', 'YourController::extras');
$routes->get('extras/search', 'YourController::search');
$routes->get('extras/export', 'YourController::exportData');
$routes->post('extras/import', 'YourController::importData');
$routes->get('extras/backup', 'YourController::backupDatabase');
$routes->get('extras/audit-log', 'YourController::auditLog');
$routes->post('extras/update-notification', 'YourController::updateNotificationSettings');
$routes->get('extras/get-notification-settings', 'YourController::getNotificationSettings');

// Pending Simpanan Pokok Routes
$routes->get('admin/pending-simpanan-pokok', 'AdminDashboard::pendingSimpananPokok');
$routes->get('admin/detail-simpanan-pokok/(:num)', 'AdminDashboard::detailSimpananPokok/$1');
$routes->post('admin/approve-simpanan-pokok/(:num)', 'AdminDashboard::approveSimpananPokok/$1');
$routes->post('admin/reject-simpanan-pokok/(:num)', 'AdminDashboard::rejectSimpananPokok/$1');

