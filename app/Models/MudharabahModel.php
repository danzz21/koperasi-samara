<?php
namespace App\Models;

use CodeIgniter\Model;

class MudharabahModel extends Model {
    protected $table = 'mudharabah';
    protected $primaryKey = 'id_md';
    protected $allowedFields = ['id_anggota', 'jml_pinjam', 'jml_angsuran', 'status', 'tanggal'];
}