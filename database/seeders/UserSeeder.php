<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => env('VITE_DEFAULT_USER_NAME'),
            'email' => env('VITE_DEFAULT_USER_EMAIL'),
            'cpf' => env('VITE_DEFAULT_USER_CPF'),
            'password' => Hash::make(env('VITE_DEFAULT_USER_PASSWORD')),
            'active' => true,
            'is_owner' => true,
            'password_changed_at' => Carbon::now()
        ]);
    }
}
