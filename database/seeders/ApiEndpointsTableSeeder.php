<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ApiEndpoint;

class ApiEndpointsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ApiEndpoint::create([
            'url' => 'https://api.example.com/endpoint',
            'method' => 'GET',
            'description' => 'This is a sample GET endpoint.',
            'headers' => json_encode([
                ['key' => 'Authorization', 'value' => 'Bearer token'],
                ['key' => 'Content-Type', 'value' => 'application/json']
            ]), // Headers as JSON array
            'payload_format' => 'JSON',
            'parameters' => json_encode([
                'id' => 123,
                'name' => 'example'
            ]),
            'status' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        ApiEndpoint::create([
            'url' => 'https://api.example.com/another-endpoint',
            'method' => 'POST',
            'description' => 'This is a sample POST endpoint.',
            'headers' => json_encode([
                ['key' => 'Authorization', 'value' => 'Bearer another-token'],
                ['key' => 'Accept', 'value' => 'application/json']
            ]), // Another example of headers as JSON array
            'payload_format' => 'JSON',
            'parameters' => json_encode([
                'action' => 'create',
                'data' => ['field1' => 'value1', 'field2' => 'value2']
            ]),
            'status' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
