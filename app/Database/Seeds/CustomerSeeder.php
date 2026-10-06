<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * Inserts the 6 sample customers.
 * Run with:  php spark db:seed CustomerSeeder
 */
class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $this->db->table('customers')->insertBatch([
            ['full_name' => 'Maria Santos',   'email' => 'maria.santos@email.com', 'phone' => '09171234567', 'created_at' => '2026-09-01 09:15:00'],
            ['full_name' => 'Jose Reyes',     'email' => 'jose.reyes@email.com',   'phone' => '09182345678', 'created_at' => '2026-09-02 10:30:00'],
            ['full_name' => 'Ana Cruz',       'email' => 'ana.cruz@email.com',     'phone' => '09193456789', 'created_at' => '2026-09-03 11:45:00'],
            ['full_name' => 'Carlo Dizon',    'email' => 'carlo.dizon@email.com',  'phone' => '09204567890', 'created_at' => '2026-09-04 13:00:00'],
            ['full_name' => 'Liza Garcia',    'email' => 'liza.garcia@email.com',  'phone' => null,          'created_at' => '2026-09-05 14:20:00'],
            ['full_name' => 'Ramon Bautista', 'email' => 'ramon.b@email.com',      'phone' => '09215678901', 'created_at' => '2026-09-06 15:10:00'],
        ]);
    }
}
