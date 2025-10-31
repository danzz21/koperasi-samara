<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'users';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'nama_lengkap', 'email', 'username', 'password', 'role', 'created_at',
        'nomor_ktp', 'foto', 'nomor_hp', 'nomor_hp_keluarga', 'status' // 🔑 tambah ini
    ];

    protected $useTimestamps = false; // kalau pakai created_at manual
}
