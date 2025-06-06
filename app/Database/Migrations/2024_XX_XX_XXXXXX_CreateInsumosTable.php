<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateInsumosTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_insumo' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'nombre' => [
                'type' => 'VARCHAR',
                'constraint' => 70,
                'null' => false,
            ],
            'tipo' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
            ],
            'cantidad' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
                'default' => 0,
            ],
            'ubicacion' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false,
            ],
            'lote' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'codigo' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
            ],
            'tiene_vencimiento' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
            'fecha_vencimiento' => [
                'type' => 'DATE',
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

        $this->forge->addKey('id_insumo', true);
        $this->forge->addKey('nombre');
        $this->forge->createTable('insumos');
    }

    public function down()
    {
        $this->forge->dropTable('insumos');
    }
}