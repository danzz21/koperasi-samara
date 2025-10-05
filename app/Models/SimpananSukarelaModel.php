<?php

namespace App\Models;

use CodeIgniter\Model;

class SimpananSukarelaModel extends Model
{
    protected $table = 'simpanan_sukarela';
    protected $primaryKey = 'id_ss';
    protected $allowedFields = ['id_anggota', 'tanggal', 'jumlah', 'bukti_transfer', 'status'];
}
