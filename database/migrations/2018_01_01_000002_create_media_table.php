<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediaTable extends Migration
{
    /**
     * This is a sample migration for a Data Model
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media', function (Blueprint $table) {
            $table->char('id', 36)->unique()->primary();
            $table->string('filename', 100)->default('');
            $table->string('format', 4)->index()->default('');
            $table->string('mimetype', 50)->index()->default('application/octet-stream');
            $table->bigInteger('size')->unsigned()->default(0);
            $table->string('url')->default('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('media');
    }
}
