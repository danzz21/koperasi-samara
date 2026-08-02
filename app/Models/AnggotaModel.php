<?php

namespace App\Models;

use CodeIgniter\Model;

class AnggotaModel extends Model
{
    protected $table      = 'anggota';
   
    protected $primaryKey = 'id_anggota';

    protected $useAutoIncrement = true;

    protected $useTimestamps = false;

    protected $allowedFields = [
        'id_anggota',
        'nomor_anggota',
        'nama_lengkap',
        'email',
        'username',
        'password',
        'no_ktp',
        'foto_ktp',
        'foto_diri',
        'foto_diri_ktp',
        'photo',
        'no_hp',
        'jenis_kelamin',
        'alamat',
        'pekerjaan',
        'instansi',
        'jenis_bank',
        'no_rek',
        'atasnama_rekening',
        'tanggal_daftar',
        'status'
    ];

    public function getAnggotaAktif()
    {
        return $this->where('status', 'aktif')->findAll();
    }

    public function getAnggotaPending()
    {
        return $this->where('status', 'pending')->findAll();
    }

    public function getByNomorAnggota($nomor_anggota)
    {
        return $this->where('nomor_anggota', $nomor_anggota)->first();
    }

    public function verifikasiAnggota($id_anggota)
    {
        return $this->update($id_anggota, ['status' => 'aktif']);
    }

    public function tolakAnggota($id_anggota)
    {
        return $this->update($id_anggota, ['status' => 'ditolak']);
    }
}