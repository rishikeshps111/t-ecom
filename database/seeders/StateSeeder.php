<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\State;

class StateSeeder extends Seeder
{
    public function run(): void
    {
        $states = [
            ['code' => '0001', 'name' => 'Johor', 'status' => 1],
            ['code' => '0002', 'name' => 'Kedah', 'status' => 1],
            ['code' => '0003', 'name' => 'Kelantan', 'status' => 1],
            ['code' => '0004', 'name' => 'Melaka', 'status' => 1],
            ['code' => '0005', 'name' => 'Negeri Sembilan', 'status' => 1],
            ['code' => '0006', 'name' => 'Pahang', 'status' => 1],
            ['code' => '0007', 'name' => 'Perak', 'status' => 1],
            ['code' => '0008', 'name' => 'Perlis', 'status' => 1],
            ['code' => '0009', 'name' => 'Penang', 'status' => 1],
            ['code' => '0010', 'name' => 'Sabah', 'status' => 1],
            ['code' => '0011', 'name' => 'Sarawak', 'status' => 1],
            ['code' => '0012', 'name' => 'Selangor', 'status' => 1],
            ['code' => '0013', 'name' => 'Terengganu', 'status' => 1],
        ];

        State::insert($states);
    }
}
