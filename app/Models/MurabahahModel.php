<?php
namespace App\Models;

use CodeIgniter\Model;

class MurabahahModel extends Model {
    protected $table = 'murabahah';
    protected $primaryKey = 'id_mr';
    protected $allowedFields = ['id_anggota', 'jml_pinjam', 'jml_angsuran', 'status', 'tanggal'];
}

