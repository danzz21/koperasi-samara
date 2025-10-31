<?php
namespace App\Models;

use CodeIgniter\Model;

class SimpananModel extends Model
{
    protected $table = 'simpanan';
    protected $primaryKey = 'id_simpanan';
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $allowedFields = [
        'id_simpanan',
        'id_anggota',
        'jenis', // pokok, wajib, sukarela
        'jumlah',
        'status',
        'tanggal',
    ];
}
