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
      public function getAngsuranWithAnggota()
{
    return $this->select('qard.*, anggota.nama_lengkap, anggota.nomor_anggota')
                ->join('anggota', 'anggota.id_anggota = qard.id_anggota')
                ->findAll();
}

public function bayarAngsuran($id_qard, $jumlah_bayar)
{
    $pinjaman = $this->find($id_qard);
    if ($pinjaman) {
        $jml_terbayar_baru = $pinjaman['jml_terbayar'] + $jumlah_bayar;
        
        // TENOR DIBAYAR bertambah 1 setiap pembayaran
        $tenor_dibayar_baru = ($pinjaman['tenor_dibayar'] ?? 0) + 1;
        
        // Status lunas jika tenor_dibayar >= total tenor ATAU total terbayar >= total pinjaman
        $status_lunas = ($tenor_dibayar_baru >= $pinjaman['jml_angsuran']) || ($jml_terbayar_baru >= $pinjaman['jml_pinjam']);
        $status = $status_lunas ? 'lunas' : 'aktif';

        $data = [
            'jml_terbayar' => $jml_terbayar_baru,
            'tenor_dibayar' => $tenor_dibayar_baru,
            'status' => $status
        ];

        return $this->update($id_qard, $data);
    }
    return false;
}
}