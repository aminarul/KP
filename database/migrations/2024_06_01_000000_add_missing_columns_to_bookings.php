<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Daftar kolom yang akan ditambahkan
     */
    protected array $columns = [
        'foto_ktp' => ['type' => 'string', 'nullable' => true, 'after' => 'paket_id'],
        'foto_rumah' => ['type' => 'string', 'nullable' => true, 'after' => 'foto_ktp'],
        'foto_modem' => ['type' => 'string', 'nullable' => true, 'after' => 'foto_rumah'],
        'type_modem' => ['type' => 'string', 'length' => 100, 'nullable' => true],
        'sn_ont' => ['type' => 'string', 'length' => 50, 'nullable' => true],
        'keterangan_pemasangan' => ['type' => 'text', 'nullable' => true],
        'tanggal_selesai' => ['type' => 'datetime', 'nullable' => true],
        'completed_at' => ['type' => 'datetime', 'nullable' => true],
    ];

    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            foreach ($this->columns as $column => $config) {
                if (!Schema::hasColumn('bookings', $column)) {
                    $this->addColumn($table, $column, $config);
                }
            }
        });
    }

    protected function addColumn(Blueprint $table, string $name, array $config): void
    {
        $column = match ($config['type']) {
            'string' => $table->string($name, $config['length'] ?? 255),
            'text' => $table->text($name),
            'datetime' => $table->datetime($name),
            default => throw new \Exception("Unknown column type: {$config['type']}"),
        };

        if ($config['nullable'] ?? false) {
            $column->nullable();
        }

        if (isset($config['after'])) {
            $column->after($config['after']);
        }
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            foreach (array_keys($this->columns) as $column) {
                if (Schema::hasColumn('bookings', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};