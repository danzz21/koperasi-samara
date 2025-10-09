<?php

namespace App\Models;

use CodeIgniter\Model;

class AngsuranQardModel extends Model
{
    protected $table = 'angsuran_qard';
    protected $primaryKey = 'id';
    protected $allowedFields = ['qard_id', 'angsuran_ke', 'jumlah_angsuran', 'tanggal_jatuh_tempo', 'tanggal_bayar', 'status', 'denda'];
    protected $useTimestamps = true;

    public function getAngsuranBerjalan($qard_id)
    {
        return $this->where('qard_id', $qard_id)
                    ->where('status', 'lunas')
                    ->countAllResults();
    }

    public function getJatuhTempoTerdekat($qard_id)
    {
        $result = $this->where('qard_id', $qard_id)
                      ->where('status', 'belum_bayar')
                      ->where('tanggal_jatuh_tempo >=', date('Y-m-d'))
                      ->orderBy('tanggal_jatuh_tempo', 'ASC')
                      ->first();

        return $result ? $result->tanggal_jatuh_tempo : null;
    }

    public function bayarAngsuran($qard_id, $angsuran_ke)
    {
        return $this->where('qard_id', $qard_id)
                   ->where('angsuran_ke', $angsuran_ke)
                   ->set([
                       'status' => 'lunas',
                       'tanggal_bayar' => date('Y-m-d H:i:s')
                   ])
                   ->update();
    }
}