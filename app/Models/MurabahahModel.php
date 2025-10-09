<?php
namespace App\Models;

use CodeIgniter\Model;

class MurabahahModel extends Model {
    protected $table = 'murabahah';
    protected $primaryKey = 'id_mr';
    protected $allowedFields = ['id_anggota', 'jml_pinjam', 'jml_angsuran', 'status', 'tanggal'];
    protected $useTimestamps = true;

    public function getPinjamanAktif($anggota_id)
    {
        return $this->where('id_anggota', $anggota_id)
                    ->where('status', 'disetujui')
                    ->findAll();
    }

    public function getPinjamanLunas($anggota_id)
    {
        return $this->where('id_anggota', $anggota_id)
                    ->where('status', 'lunas')
                    ->findAll();
    }
}