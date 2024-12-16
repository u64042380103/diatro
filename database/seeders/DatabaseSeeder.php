<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::create(
        \App\Models\User::truncate();

        $users =  [
            [
                'name' => 'AdminDragonHispeed',
                'username' => 'admin',
                'imgpro' => '/assets/img/accounts/avatar1.png',
                'email' => 'admin@dragonhispeed.com',
                'password' => Hash::make('abc112233'),
            ],
            [
                'name' => 'Demo01',
                'username' => 'demo01',
                'imgpro' => '/assets/img/accounts/avatar2.png',
                'email' => 'demo01@dragonhispeed.com',
                'password' => Hash::make('xyz112233')
            ],
            [
                'name' => 'Demo02',
                'username' => 'demo02',
                'imgpro' => '/assets/img/accounts/avatar3.png',
                'email' => 'demo02@dragonhispeed.com',
                'password' => Hash::make('xyz112233')
            ],
            [
                'name' => 'Demo03',
                'username' => 'demo03',
                'imgpro' => '/assets/img/accounts/avatar4.png',
                'email' => 'demo03@dragonhispeed.com',
                'password' => Hash::make('xyz112233')
            ]
        ];

        \App\Models\User::insert($users);
    }
}
