<?php
namespace App\Models;

use CodeIgniter\Model;

class SimpananPokokModel extends Model
{
    protected $table = 'simpanan_pokok';
    protected $primaryKey = 'id_simpanan_pokok';
    protected $allowedFields = ['id_anggota', 'jumlah', 'tanggal', 'status'];
    
    public function total()
    {
        return $this->selectSum('jumlah')->get()->getRow()->jumlah ?? 0;
    }
    // Cek apakah anggota sudah mencapai batas simpanan pokok
    public function isLunas($id_anggota)
    {
        $total = $this->where('id_anggota', $id_anggota)
                     ->selectSum('jumlah')
                     ->get()
                     ->getRow()->jumlah;
        
        return $total >= 500000;
    }
    
    // Get total simpanan pokok anggota
    public function getTotalSimpananPokok($id_anggota)
    {
        $result = $this->where('id_anggota', $id_anggota)
                      ->selectSum('jumlah')
                      ->get()
                      ->getRow();
        
        return $result->jumlah ?: 0;
    }
    
    // Hitung sisa simpanan pokok yang bisa diinput
    public function getSisaSimpananPokok($id_anggota)
    {
        $total = $this->getTotalSimpananPokok($id_anggota);
        $sisa = 500000 - $total;
        return max(0, $sisa); // Tidak boleh minus
    }
    
    // Validasi input simpanan pokok
    public function validateInput($id_anggota, $jumlah)
    {
        $total = $this->getTotalSimpananPokok($id_anggota);
        $newTotal = $total + $jumlah;
        
        if ($newTotal > 500000) {
            return [
                'valid' => false,
                'message' => 'Total simpanan pokok tidak boleh melebihi Rp 500.000. Sisa yang bisa diinput: Rp ' . number_format(500000 - $total, 0, ',', '.')
            ];
        }
        
        return [
            'valid' => true,
            'message' => 'OK'
        ];
    }
    
    // Update status simpanan pokok
    public function updateStatus($id_anggota)
    {
        $total = $this->getTotalSimpananPokok($id_anggota);
        
        if ($total >= 500000) {
            // Update semua record simpanan pokok anggota menjadi lunas
            $this->where('id_anggota', $id_anggota)
                 ->set('status', 'lunas')
                 ->update();
            return true;
        }
        return false;
    }

    
}