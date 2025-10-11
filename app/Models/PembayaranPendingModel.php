<?php
namespace App\Models;

use CodeIgniter\Model;

class PembayaranPendingModel extends Model
{
    protected $table = 'pembayaran_pending';
    protected $primaryKey = 'id_pembayaran';
    protected $allowedFields = [
        'id_anggota', 
        'jenis_pinjaman', 
        'id_pinjaman', 
        'angsuran_ke', 
        'jumlah_bayar', 
        'bukti_bayar', 
        'tanggal_bayar', 
        'status',
        'created_at'
    ];
    protected $useTimestamps = false;

    public function getByAnggota($id_anggota)
    {
        return $this->where('id_anggota', $id_anggota)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }
}