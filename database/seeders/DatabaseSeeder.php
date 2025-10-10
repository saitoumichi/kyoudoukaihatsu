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
        $userId = DB::table('users')->insertGetId([
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

        // Placesのサンプルデータを追加
        DB::table('places')->insert([
            [
                'user_id'          => $userId,
                'type'             => 'drive',
                'name'             => 'イオンモール草津',
                'address'          => '滋賀県草津市新浜町300',
                'campus_time_min'  => 15,
                'description'      => 'BKCから近い大型ショッピングモール',
                'is_active'        => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'user_id'          => $userId,
                'type'             => 'karaoke',
                'name'             => 'カラオケまねきねこ南草津店',
                'address'          => '滋賀県草津市野路1-15-5',
                'campus_time_min'  => 10,
                'description'      => '南草津駅近くの人気カラオケ',
                'is_active'        => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'user_id'          => $userId,
                'type'             => 'izakaya',
                'name'             => '鳥貴族南草津店',
                'address'          => '滋賀県草津市野路1-15-3',
                'campus_time_min'  => 10,
                'description'      => '均一価格の焼き鳥居酒屋',
                'is_active'        => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
        ]);

        // フリマ商品のサンプルデータを追加
        $this->call(FreeMarketSeeder::class);
    }
}
