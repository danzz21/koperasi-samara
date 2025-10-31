<?php
namespace App\Models;

use CodeIgniter\Model;

class SimpananWajibModel extends Model
{
    protected $table = 'simpanan_wajib';
    protected $primaryKey = 'id_sw';
    protected $useTimestamps = false;
    protected $allowedFields = [
        'id_sw',
        'id_anggota',
        'jumlah',
        'tanggal',
        'status',
    ];

     public function total()
    {
        return $this->selectSum('jumlah')->get()->getRow()->jumlah ?? 0;
    }

    // Get total simpanan wajib anggota
    public function getTotalSimpananWajib($id_anggota)
    {
        $result = $this->where('id_anggota', $id_anggota)
                       ->selectSum('jumlah')
                       ->get()
                       ->getRow();

        return $result->jumlah ?: 0;
    }
}
