<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEntitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entities', function (Blueprint $table) {
            $table->string('_id', 36)->unique();
            $table->string('parent', 36)->index()->default('');
            $table->string('model', 100)->index();
            $table->enum('active', ['active', 'inactive', 'draft', 'deleted'])->index()->default('active');
            $table->string('created_by', 36)->default('system');
            $table->string('updated_by', 36)->default('system');
            $table->dateTime('publicated_at')->nullable()->default(date("Y-m-d H:i:s"));
            $table->dateTime('unpublicated_at')->nullable()->default('9999-12-31 23:59;59');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('entities');
    }
}
