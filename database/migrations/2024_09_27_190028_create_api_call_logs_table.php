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
        Schema::create('api_call_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('api_id')->nullable();
            $table->string('api_name'); // API Name
            $table->enum('method', ['GET', 'POST', 'PUT', 'DELETE']); // HTTP method
            $table->integer('call_count')->default(0); // Number of calls made to the API
            $table->decimal('average_response_time', 8, 2)->default(0.00); // Average response time in seconds (two decimal points)
            $table->enum('status', ['Success', 'Failed'])->default('Success'); // Status (Success or Failed)
            $table->timestamps(); // created_at and updated_at timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_call_logs');
    }
};
