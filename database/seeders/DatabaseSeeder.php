<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
       $adminExists = User::where('email', 'admin@primestage.com')->exists();

        if (!$adminExists) {
            User::create([
                'first_name' => 'Admin',
                'last_name' => 'PrimeStage',
                'email' => 'admin@primestage.com',
                'phone' => '081234567890',
                'password' => Hash::make('admin12345'),
                'role' => 'admin',
                'status' => 'active',
            ]);

            echo "✓ Admin user berhasil dibuat!\n";
            echo "Email: admin@primestage.com\n";
            echo "Password: admin12345\n";
        } else {
            echo "! Admin user sudah ada di database.\n";
        }
    }
}
