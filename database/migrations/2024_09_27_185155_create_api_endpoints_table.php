<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('api_endpoints', function (Blueprint $table) {
            $table->id();
            $table->string('url'); // URL endpoint
            $table->enum('method', ['GET', 'POST', 'PUT', 'DELETE']); // HTTP method selection
            $table->text('description')->nullable(); // Description of the endpoint
            $table->json('headers')->nullable(); // JSON field for headers (Key-Value pairs)
            $table->enum('payload_format', ['JSON', 'XML'])->nullable(); // Payload format (JSON or XML)
            $table->json('parameters')->nullable(); // Optional parameters as key-value pairs (limit, offset, etc.)
            $table->boolean('status')->default(true); // Status (Enable/Disable toggle, default enabled)
            $table->timestamps(); // created_at and updated_at timestamps
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_endpoints');
    }
};
