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
        $user = User::first();
        if ($user) {
            DB::table('addresses')->insert([
                'user_id'    => $user->id,
                'postal_code' => 'XXX-YYYY',
                'address'     => 'ここには住所と',
                'building'    => '建物が入ります',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
