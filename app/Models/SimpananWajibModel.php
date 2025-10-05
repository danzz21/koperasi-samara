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
}
