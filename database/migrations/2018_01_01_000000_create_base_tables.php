<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->uuid('id')->unique()->primary();
            $table->string('model', 100)->index();
            $table->string('name', 255)->default('');
            $table->char('parent_id', 36)->index()->default('');
            $table->boolean('active')->index()->default(true);
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
            $table->string('id', 100)->primary();
            $table->char('entity_id', 36)->index();
            $table->char('lang', 5)->default('')->index();
            $table->string('field', 100)->index();
            $table->timestamps();
            $table->mediumText('value', 100);
        });
        Schema::create('relations', function (Blueprint $table) {
            $table->uuid('caller_id')->index();
            $table->uuid('called_id')->index();
            $table->enum('kind', ['relation', 'ancestor', 'category', 'medium', 'home', 'favorite', 'like', 'follow'])
                  ->index()
                  ->default('relation')
            ;
            $table->integer('position')->default(0);
            $table->integer('depth')->default(0);
            $table->text('tags');
            $table->primary(['caller_id', 'called_id', 'kind']);
            $table->timestamps();
        });
        DB::statement('ALTER TABLE contents ADD FULLTEXT contents_index (value)');
        DB::statement('ALTER TABLE relations ADD FULLTEXT tags_index (tags)');
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
