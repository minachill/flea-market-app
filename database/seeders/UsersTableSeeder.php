<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'ダミーユーザー',
            'email' => 'dummy@example.com',
            'password' => Hash::make('password'), // 本番ではログイン不可でもOK
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
    ]);
    }
}
