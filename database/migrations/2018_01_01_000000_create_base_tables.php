<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBaseTables extends Migration
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
            $table->string('model', 100)->index();
            $table->string('name', 255);
            $table->char('parent', 36)->index()->default('');
            $table->bigInteger('position')->default(0);
            $table->boolean('isActive')->index()->default(true);
            $table->char('created_by', 36)->default('system');
            $table->char('updated_by', 36)->default('system');
            $table->dateTime('publicated_at')->nullable()->default(date("Y-m-d H:i:s"));
            $table->dateTime('unpublicated_at')->nullable()->default('9999-12-31 23:59;59');
            $table->integer('entity_version')->unsigned()->default(0);
            $table->integer('tree_version')->unsigned()->default(0);
            $table->integer('relations_version')->unsigned()->default(1);
            $table->integer('full_version')->unsigned()->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('contents', function (Blueprint $table) {
            $table->char('entity_id', 36)->index();
            $table->string('lang', 5)->index()->default('');
            $table->string('field', 100)->index();
            $table->mediumText('value', 100);
            $table->primary(['entity_id', 'lang', 'field']);
        });
        Schema::create('relations', function (Blueprint $table) {
            $table->char('entity_caller_id', 36)->index();
            $table->char('entity_called_id', 36)->index();
            $table->enum('kind', ['relation', 'ancestor', 'category', 'medium', 'home', 'favorite', 'like', 'follow'])->index()->default('relation');
            $table->integer('position')->default(0);
            $table->integer('depth')->default(0);
            $table->string('tags', 255);
            $table->primary(['entity_caller_id', 'entity_called_id', 'kind']);
            $table->timestamps();
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
        Schema::dropIfExists('contents');
        Schema::dropIfExists('relations');
    }
}
