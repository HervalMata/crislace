<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Customer1',
            'email' => 'customer1@crislace.com',
            'password' => Hash::make('123456'),
            'cpf' => '9999999999',
            'type' => 0
        ]);

        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@crislace.com',
            'password' => Hash::make('123456'),
            'cpf' => '8888888888',
            'type' => 1
        ]);

        DB::table('users')->insert([
            'name' => 'Seller1',
            'email' => 'seller1@crislace.com',
            'password' => Hash::make('123456'),
            'cpf' => '7777777777',
            'type' => 2
        ]);
    }
}
