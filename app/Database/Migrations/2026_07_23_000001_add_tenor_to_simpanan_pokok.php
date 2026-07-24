<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTenorToSimpananPokok extends Migration
{
    public function up()
    {
        $fields = [
            'tenor' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
                'after' => 'status',
            ],
        ];

        $this->forge->addColumn('simpanan_pokok', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('simpanan_pokok', 'tenor');
    }
}
