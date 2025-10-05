<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSimpananTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'anggota_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
            ],
            'jenis_simpanan' => [
                'type' => 'ENUM',
                'constraint' => ['pokok', 'wajib', 'sukarela'],
                'null' => false,
            ],
            'jumlah' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'null' => false,
            ],
            'tanggal' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'selesai'],
                'default' => 'selesai',
            ],
            'keterangan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('anggota_id', 'anggota', 'id_anggota', 'CASCADE', 'CASCADE');
        $this->forge->createTable('simpanan');
    }

    public function down()
    {
        $this->forge->dropTable('simpanan');
    }
}