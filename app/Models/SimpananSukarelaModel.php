<?php

namespace App\Models;

use CodeIgniter\Model;

class SimpananSukarelaModel extends Model
{
    protected $table = 'simpanan_sukarela';
    protected $primaryKey = 'id_ss';
    protected $allowedFields = ['id_anggota', 'tanggal', 'jumlah', 'bukti', 'status'];

    public function total()
    {
        return $this->selectSum('jumlah')->get()->getRow()->jumlah ?? 0;
    }

    // Get total simpanan sukarela anggota
    public function getTotalSimpananSukarela($id_anggota)
    {
        $result = $this->where('id_anggota', $id_anggota)
                       ->selectSum('jumlah')
                       ->get()
                       ->getRow();

        return $result->jumlah ?: 0;
    }
}


