<?php
namespace App\Models;

use CodeIgniter\Model;

class AngsuranMudharabahModel extends Model {
    protected $table = 'angsuran_mudharabah';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_md', 'angsuran_ke', 'jumlah_angsuran', 'tanggal_jatuh_tempo', 'tanggal_bayar', 'status', 'denda'];
    protected $useTimestamps = true;

    public function getAngsuranBerjalan($mudharabah_id)
    {
        return $this->where('id_md', $mudharabah_id)
                    ->where('status', 'lunas')
                    ->countAllResults();
    }

    public function getJatuhTempoTerdekat($mudharabah_id)
    {
        $result = $this->where('id_md', $mudharabah_id)
                      ->where('status', 'belum_bayar')
                      ->where('tanggal_jatuh_tempo >=', date('Y-m-d'))
                      ->orderBy('tanggal_jatuh_tempo', 'ASC')
                      ->first();

        return $result ? $result->tanggal_jatuh_tempo : null;
    }

    public function bayarAngsuran($mudharabah_id, $angsuran_ke)
    {
        return $this->where('id_md', $mudharabah_id)
                   ->where('angsuran_ke', $angsuran_ke)
                   ->set([
                       'status' => 'lunas',
                       'tanggal_bayar' => date('Y-m-d H:i:s')
                   ])
                   ->update();
    }
}