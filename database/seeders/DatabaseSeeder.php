<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'login_id'          => 'testuser',
            'email'             => 'test@example.com',
            'password_hash'     => Hash::make('password123'),
            'password_algo'     => 'bcrypt',
            'role'              => 'admin',
            'is_active'         => 1,
            'email_verified_at' => now(),
            'remember_token'    => Str::random(10),
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        // フリマ商品のサンプルデータを追加
        $this->call(FreeMarketSeeder::class);
    }
}
