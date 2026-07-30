<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailAngsuranModel extends Model
{
    protected $table            = 'detail_angsuran';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object'; // atau 'array' sesuai selera Anda
    protected $allowedFields    = [
        'jenis_pembiayaan',
        'id_pembiayaan',
        'id_anggota',
        'angsuran_ke',
        'jumlah_bayar',
        'tanggal_bayar',
        'denda',
        'keterangan'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Helper untuk mengambil riwayat angsuran berdasarkan jenis dan ID pembiayaan
     */
    public function getRiwayat($jenis, $idPembiayaan)
    {
        return $this->where('jenis_pembiayaan', $jenis)
                    ->where('id_pembiayaan', $idPembiayaan)
                    ->orderBy('angsuran_ke', 'ASC')
                    ->findAll();
    }
}