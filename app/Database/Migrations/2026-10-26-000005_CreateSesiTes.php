<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSesiTes extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'peserta_id'    => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'waktu_mulai'   => ['type' => 'DATETIME'],
            'waktu_selesai' => ['type' => 'DATETIME'],
            'waktu_kirim'   => ['type' => 'DATETIME', 'null' => true],
            'status'        => ['type' => 'ENUM', 'constraint' => ['berjalan', 'selesai'], 'default' => 'berjalan'],
            'jumlah_soal'   => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'jumlah_benar'  => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            'skor'          => ['type' => 'DECIMAL', 'constraint' => '5,2', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        // satu peserta hanya boleh punya satu sesi (tidak bisa mengulang tes)
        $this->forge->addUniqueKey('peserta_id');
        $this->forge->addKey('status');
        $this->forge->createTable('sesi_tes');
    }

    public function down()
    {
        $this->forge->dropTable('sesi_tes', true);
    }
}
