<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePengaturan extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'           => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'durasi_menit' => ['type' => 'INT', 'constraint' => 11, 'default' => 30],
            'jumlah_soal'  => ['type' => 'INT', 'constraint' => 11, 'default' => 50],
            'acak_soal'    => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'tes_dibuka'   => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'tampil_skor'  => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('pengaturan');
    }

    public function down()
    {
        $this->forge->dropTable('pengaturan', true);
    }
}
