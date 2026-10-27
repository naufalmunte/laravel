<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class OwnerSeeder extends Seeder
{
    public function run(): void
    {
        $name = env('OWNER_NAME');
        $email = env('OWNER_EMAIL');
        $password = env('OWNER_PASSWORD');

        if (!$name || !$email || !$password) {
            throw new RuntimeException(
                'OWNER_NAME, OWNER_EMAIL, dan OWNER_PASSWORD wajib diisi di file .env.'
            );
        }

        $existingUser = User::where('email', $email)->first();

        if ($existingUser) {
            if ($existingUser->role !== 'owner') {
                throw new RuntimeException(
                    'Email Owner sudah digunakan akun dengan role berbeda. Gunakan email lain.'
                );
            }

            $this->command->info('Akun Owner sudah tersedia.');
            return;
        }

        User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'role' => 'owner',
            'email_verified_at' => now(),
        ]);

        $this->command->info('Akun Owner berhasil dibuat.');
    }
}
