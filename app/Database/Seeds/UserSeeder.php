<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * Inserts the 6 sample users, each with a password hashed by password_hash().
 * Run with:  php spark db:seed UserSeeder
 *
 * Demo password for every user:  password123
 */
class UserSeeder extends Seeder
{
    public const DEMO_PASSWORD = 'password123';

    public function run(): void
    {
        $users = [
            ['username' => 'admin',     'full_name' => 'System Administrator', 'created_at' => '2026-09-01 08:00:00', 'avatar' => 'sample-admin.png'],
            ['username' => 'cashier1',  'full_name' => 'Bea Lopez',            'created_at' => '2026-09-01 08:05:00', 'avatar' => 'sample-cashier1.png'],
            ['username' => 'cashier2',  'full_name' => 'Mark Villanueva',      'created_at' => '2026-09-01 08:10:00', 'avatar' => null],
            ['username' => 'manager',   'full_name' => 'Grace Tan',            'created_at' => '2026-09-01 08:15:00', 'avatar' => null],
            ['username' => 'inventory', 'full_name' => 'Paulo Mendoza',        'created_at' => '2026-09-01 08:20:00', 'avatar' => null],
            ['username' => 'support',   'full_name' => 'Nina Ramos',           'created_at' => '2026-09-01 08:25:00', 'avatar' => null],
        ];

        foreach ($users as &$user) {
            // A different salt is generated for each user, so every hash is unique.
            $user['password'] = password_hash(self::DEMO_PASSWORD, PASSWORD_DEFAULT);
        }
        unset($user);

        $this->db->table('users')->insertBatch($users);
    }
}
