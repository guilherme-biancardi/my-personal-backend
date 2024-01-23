<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SellerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('sellers')->insert([
            'name' => env('VITE_DEFAULT_USER_NAME'),
            'phone_number' => env('VITE_DEFAULT_USER_PHONE_NUMBER'),
            'cpf' => env('VITE_DEFAULT_USER_CPF'),
        ]);
    }
}
