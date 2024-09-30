<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateHeadersToJsonInEndpointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('api_endpoints', function (Blueprint $table) {
            $table->json('headers')->change(); // Modify the headers column to JSON
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('api_endpoints', function (Blueprint $table) {
            $table->text('headers')->change(); // Revert back to text if needed
        });
    }
}
