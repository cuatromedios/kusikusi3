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
            $table->char('id', 36)->unique();
            $table->char('parent', 36)->index()->default('');
            $table->bigInteger('position')->default(0);
            $table->string('model', 100)->index();
            $table->string('name', 255);
            $table->boolean('isActive')->index()->default(true);
            $table->char('created_by', 36)->default('system');
            $table->char('updated_by', 36)->default('system');
            $table->dateTime('publicated_at')->nullable()->default(date("Y-m-d H:i:s"));
            $table->dateTime('unpublicated_at')->nullable()->default('9999-12-31 23:59;59');
            $table->integer('entity_version')->unsigned()->default(1);
            $table->integer('tree_version')->unsigned()->default(1);
            $table->integer('relations_version')->unsigned()->default(1);
            $table->integer('full_version')->unsigned()->default(1);
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
