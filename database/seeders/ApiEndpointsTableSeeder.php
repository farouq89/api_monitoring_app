<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class ApiEndpointsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('api_endpoints')->insert([
            [
                'url' => '/users',
                'method' => 'GET',
                'description' => 'Retrieve user list',
                'headers' => json_encode(['Content-Type' => 'application/json']),
                'payload_format' => null,
                'parameters' => json_encode(['limit' => 10, 'offset' => 0]),
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'url' => '/users',
                'method' => 'POST',
                'description' => 'Create a new user',
                'headers' => json_encode(['Content-Type' => 'application/json']),
                'payload_format' => 'JSON',
                'parameters' => json_encode([]),
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
