<?php
namespace App\Models;

use CodeIgniter\Model;

class MudharabahModel extends Model
{
    protected $table = 'mudharabah';
    protected $primaryKey = 'id_md';
    protected $allowedFields = [
        'id_anggota',
        'jml_pinjam',
        'jml_angsuran',
        'jml_terbayar',
        'tanggal',
        'status',
        'keperluan',
        'tgl_pengajuan',
        'tgl_disetujui',
        'disetujui_oleh'
    ];
    protected $useTimestamps = false;

    public function getPinjamanAktif($id_anggota)
    {
        return $this->where('id_anggota', $id_anggota)
                    ->where('status', 'aktif')
                    ->findAll();
    }

    public function getPinjamanPending($id_anggota)
    {
        return $this->where('id_anggota', $id_anggota)
                    ->where('status', 'pending')
                    ->findAll();
    }

    public function updateTerbayar($id_md, $jumlah_bayar)
    {
        $pinjaman = $this->find($id_md);
        if ($pinjaman) {
            $terbayar_baru = ($pinjaman['jml_terbayar'] ?? 0) + $jumlah_bayar;
            $data = ['jml_terbayar' => $terbayar_baru];
            
            if ($terbayar_baru >= $pinjaman['jml_pinjam']) {
                $data['status'] = 'lunas';
            }
            
            return $this->update($id_md, $data);
        }
        return false;
    }

    public function getTotalPinjamanAktif()
    {
        return $this->where('status', 'aktif')
                    ->selectSum('jml_pinjam')
                    ->get()
                    ->getRow()->jml_pinjam ?? 0;
    }
}