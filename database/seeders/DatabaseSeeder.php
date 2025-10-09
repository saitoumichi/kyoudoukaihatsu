<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'login_id' => 'testuser',
            'email' => 'test@example.com',
            'password_hash' => bcrypt('password123'),
            'password_algo' => 'bcrypt',
            'role' => 'admin',
            'is_active' => 1,
        ]);

        // フリマ商品のサンプルデータを追加
        $this->call(FreeMarketSeeder::class);
    }
}
