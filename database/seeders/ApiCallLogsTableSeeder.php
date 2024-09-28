<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class ApiCallLogsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('api_call_logs')->insert([
            [
                'api_name' => '/users',
                'method' => 'GET',
                'call_count' => 50,
                'average_response_time' => 1.25,
                'status' => 'Success',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'api_name' => '/users',
                'method' => 'POST',
                'call_count' => 30,
                'average_response_time' => 1.75,
                'status' => 'Failed',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
