<?php
namespace App\Models;

use CodeIgniter\Model;

class SimpananPokokModel extends Model
{
    protected $table = 'simpanan_pokok';
    protected $primaryKey = 'id_sp';
    protected $useTimestamps = false;
    protected $allowedFields = [
        'id_sp',
        'id_anggota',
        'jumlah',
        'tanggal',
        'status',
        'created_at',
        'updated_at',
    ];
}
