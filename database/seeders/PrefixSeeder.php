<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PrefixSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        DB::table('prefixes')->insert([
            [
                'module'     => 'user',
                'prefix'     => 'USR',
                'year'       => date('Y'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'module'     => 'document_manager',
                'prefix'     => 'DOCM',
                'year'       => date('Y'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'module'     => 'dealer',
                'prefix'     => 'DEAL',
                'year'       => date('Y'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'module'     => 'planner',
                'prefix'     => 'PLAN',
                'year'       => date('Y'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'module'     => 'business_owner',
                'prefix'     => 'BUSIO',
                'year'       => date('Y'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'module'     => 'customer',
                'prefix'     => 'CUS',
                'year'       => date('Y'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'module'     => 'company',
                'prefix'     => 'COMP',
                'year'       => date('Y'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'module'     => 'item',
                'prefix'     => 'ITM',
                'year'       => date('Y'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'module'     => 'invoice',
                'prefix'     => 'INV',
                'year'       => date('Y'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'module'     => 'quotation',
                'prefix'     => 'QT',
                'year'       => date('Y'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'module'     => 'payment',
                'prefix'     => 'PAY',
                'year'       => date('Y'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'module'     => 'project',
                'prefix'     => 'PROJ',
                'year'       => date('Y'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
