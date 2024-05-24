<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //管理者ユーザー
        User::create([
            'name' => '藤井健一朗',
            'email' => 'miragino.verycute@gmail.com',
            'password' => Hash::make('kenichi1010'),
        ]);
    }
}
