<?php

namespace App\Models;

use CodeIgniter\Model;

class AnggotaModel extends Model
{
    protected $table = 'anggota';
    protected $primaryKey = 'id_anggota';

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $allowedFields = [
    'id_anggota',  // ✅ Tambahkan ini di baris paling atas atau sesuai urutan
    'nomor_anggota',
    'nama_lengkap',
    'email',
    'username',
    'password',
    'no_ktp',
    'foto_ktp',
    'foto_diri',
    'no_hp',
    'no_hp_opsional',
    'alamat',
    'atasnama_rekening',
    'no_rek',
    'instansi',
    'tanggal_daftar',
    'status',
    'jenis_kelamin',
    'pekerjaan',
    'photo'
];

}
