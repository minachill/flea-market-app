<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class AddressesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::where('email', 'dummy@example.com')->first();
        if ($user) {
            DB::table('addresses')->insert([
                'user_id'    => $user->id,
                'postal_code' => '222-2222',
                'address' => '大阪府大阪市テスト町2-2-2',
                'building' => 'テストハイツ202',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $user2 = User::where('email', 'user2@example.com')->first();
        if ($user2) {
            DB::table('addresses')->insert([
                'user_id' => $user2->id,
                'postal_code' => '123-4567',
                'address'     => 'ここには住所と',
                'building'    => '建物が入ります',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
