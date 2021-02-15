<?php

use Illuminate\Database\Seeder;

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
            ['name' => 'ジョブズ',
            'email' => 'user1@example.com',
            'sex' => '0',
            'age' => '30',
            'self_introduction' => 'ジョブズです',
            'password' => Hash::make('password123'),
            ],
            ['name' => 'ザッカーバーグ',
            'email' => 'user2@example.com',
            'sex' => '1',
            'age' => '28',
            'self_introduction' => 'ザッカーバーグです',
            'password' => Hash::make('password123'),
            ],
            ['name' => 'ジェフペゾフ',
            'email' => 'user3@example.com',
            'sex' => '0',
            'age' => '24',
            'self_introduction' => 'ジェフですです',
            'password' => Hash::make('password123'),
            ],
            ['name' => 'イーロン',
            'email' => 'user4@example.com',
            'sex' => '0',
            'age' => '43',
            'self_introduction' => 'イーロンです',
            'password' => Hash::make('password123'),
            ],
        ]);
    }
}
