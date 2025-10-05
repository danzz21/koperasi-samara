<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Auth Routes
$routes->get('login/admin', 'Auth::login');
$routes->get('login', 'Auth::login');
$routes->post('auth/doLogin', 'Auth::doLogin'); // proses login
$routes->get('logout', 'Auth::logout'); // logout

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

    // Simpanan jenis
    $routes->get('sim_pokok', 'Sim_pokok::index');
    $routes->get('sim_wajib', 'Sim_wajib::index');
    $routes->get('sim_sukarela', 'Sim_sukarela::index');
    $routes->post('sim_sukarela/store', 'Sim_sukarela::store');


    // Pinjaman jenis
    $routes->get('pin_alqordh', 'Pin_alqordh::index');
    $routes->get('pin_murobahah', 'Pin_murobahah::index');
    $routes->get('pin_mudhorobah', 'Pin_mudhorobah::index');
    $routes->post('ajukan-pinjaman', 'Pinjaman::ajukan');
});


// ===========================
// ROUTE UNTUK ADMIN
// ===========================
$routes->group('admin', ['filter' => 'role:admin'], function ($routes) {
    $routes->get('/', 'AdminDashboard::index');
    $routes->get('dashboard', 'AdminDashboard::index');
    $routes->get('dashboard_admin/members', 'AdminDashboard::members');
    $routes->post('dashboard_admin/simpan', 'AdminDashboard::simpan');
    $routes->get('search-anggota', 'AdminDashboard::searchAnggota'); //
    $routes->get('dashboard_admin/savings', 'AdminDashboard::savings');
    $routes->get('dashboard_admin/financing', 'AdminDashboard::financing');
    $routes->get('dashboard_admin/transactions', 'AdminDashboard::transactions');
    $routes->get('dashboard_admin/reports', 'AdminDashboard::reports');
    $routes->get('dashboard_admin/settings', 'AdminDashboard::settings');
    $routes->get('dashboard_admin/extras', 'AdminDashboard::extras');
    $routes->get('pending-members', 'AdminDashboard::pendingMembers');
    $routes->get('verify/(:num)', 'AdminDashboard::verify/$1');
    $routes->get('reject/(:num)', 'AdminDashboard::reject/$1');

$routes->post('admin/dashboard_admin/transactions/simpan', 'Transaksi::simpanTransaksi');



    // Pinjaman Routes
    $routes->get('pending-pinjaman', 'AdminDashboard::pendingLoans');
$routes->get('pinjaman/verifikasi/(:segment)/(:num)', 'AdminDashboard::verifikasiPinjaman/$1/$2');
$routes->get('pinjaman/tolak/(:segment)/(:num)', 'AdminDashboard::tolakPinjaman/$1/$2');

// Simpanan Sukarela Routes
$routes->get('pending-sukarela', 'AdminSimpanan::pending');
$routes->get('approve-sukarela/(:num)', 'AdminSimpanan::approve/$1');
$routes->get('reject-sukarela/(:num)', 'AdminSimpanan::reject/$1');
});

