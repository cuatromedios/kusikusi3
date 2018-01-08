<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRelationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('relations', function (Blueprint $table) {
            $table->char('entity_caller_id', 36)->index();
            $table->char('entity_called_id', 36)->index();
            $table->enum('kind', ['relation', 'ancestor', 'category', 'medium'])->index()->default('relation');
            $table->integer('position')->default(0);
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
        Schema::dropIfExists('relations');
    }
}
