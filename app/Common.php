<?php

/**
 * The goal of this file is to allow developers a location
 * where they can overwrite core procedural functions and
 * replace them with their own. This file is loaded during
 * the bootstrap process and is called during the framework's
 * execution.
 *
 * This can be looked at as a `master helper` file that is
 * loaded early on, and may also contain additional functions
 * that you'd like to use throughout your entire application
 *
 * @see: https://codeigniter.com/user_guide/extending/common.html
 */

if (!function_exists('normalizeLoanStatusValue')) {
    function normalizeLoanStatusValue($status): string
    {
        $status = strtolower(trim((string)($status ?? '')));

        if ($status === '') {
            return 'pending';
        }

        $pendingStatuses = ['pending', 'menunggu', 'menunggu acc', 'review', 'diproses', 'waiting', 'proses'];
        $activeStatuses = ['aktif', 'active', 'approved', 'disetujui', 'verified', 'setuju', 'acc', 'berhasil', 'confirmed', 'terkonfirmasi', 'sukses'];
        $rejectedStatuses = ['ditolak', 'tolak', 'rejected', 'reject', 'batal', 'cancel', 'nonaktif', 'tidak disetujui', 'declined'];
        $lunasStatuses = ['lunas', 'completed', 'selesai', 'done'];

        if (in_array($status, $pendingStatuses, true)) {
            return 'pending';
        }

        if (in_array($status, $activeStatuses, true)) {
            return 'aktif';
        }

        if (in_array($status, $rejectedStatuses, true)) {
            return 'ditolak';
        }

        if (in_array($status, $lunasStatuses, true)) {
            return 'lunas';
        }

        if (strpos($status, 'tolak') !== false || strpos($status, 'reject') !== false || strpos($status, 'batal') !== false || strpos($status, 'cancel') !== false || strpos($status, 'declin') !== false) {
            return 'ditolak';
        }

        return 'pending';
    }
}
