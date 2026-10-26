<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateJawaban extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'      => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'sesi_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'soal_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'urutan'  => ['type' => 'INT', 'constraint' => 11],
            'pilihan' => ['type' => 'ENUM', 'constraint' => ['A', 'B', 'C'], 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        // satu soal hanya punya satu jawaban per sesi
        $this->forge->addUniqueKey(['sesi_id', 'soal_id']);
        $this->forge->addUniqueKey(['sesi_id', 'urutan']);
        $this->forge->createTable('jawaban');
    }

    public function down()
    {
        $this->forge->dropTable('jawaban', true);
    }
}
