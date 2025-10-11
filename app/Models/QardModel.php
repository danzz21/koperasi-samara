<?php
namespace App\Models;

use CodeIgniter\Model;

class QardModel extends Model
{
    protected $table = 'qard';
    protected $primaryKey = 'id_qard';
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

    public function updateTerbayar($id_qard, $jumlah_bayar)
    {
        $pinjaman = $this->find($id_qard);
        if ($pinjaman) {
            $terbayar_baru = ($pinjaman['jml_terbayar'] ?? 0) + $jumlah_bayar;
            $data = ['jml_terbayar' => $terbayar_baru];
            
            // Cek jika sudah lunas
            if ($terbayar_baru >= $pinjaman['jml_pinjam']) {
                $data['status'] = 'lunas';
            }
            
            return $this->update($id_qard, $data);
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