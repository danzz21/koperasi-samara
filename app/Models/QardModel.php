<?php
namespace App\Models;

use CodeIgniter\Model;

class QardModel extends Model {
    protected $table = 'qard';
    protected $primaryKey = 'id_qard';
    protected $allowedFields = ['id_anggota', 'jml_pinjam', 'jml_angsuran', 'status', 'tanggal'];
}

