<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSoal extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'nomor_urut' => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            'teks_soal'  => ['type' => 'TEXT'],
            'opsi_a'     => ['type' => 'VARCHAR', 'constraint' => 255],
            'opsi_b'     => ['type' => 'VARCHAR', 'constraint' => 255],
            'opsi_c'     => ['type' => 'VARCHAR', 'constraint' => 255],
            'kunci'      => ['type' => 'ENUM', 'constraint' => ['A', 'B', 'C']],
            'aktif'      => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'kategori'   => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('aktif');
        $this->forge->createTable('soal');
    }

    public function down()
    {
        $this->forge->dropTable('soal', true);
    }
}
