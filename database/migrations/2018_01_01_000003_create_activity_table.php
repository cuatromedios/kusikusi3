<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivityTable extends Migration
{
    /**
     * This is a sample migration for a Data Model
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity', function (Blueprint $table) {
            $table->char('user_id', 36)->index();
            $table->char('entity_id', 36)->index();
            $table->string('action', 100)->index();
            $table->boolean('isSuccess')->index();
            $table->string('subaction', 100)->index()->nullable();
            $table->mediumText('metadata')->nullable();
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
        Schema::dropIfExists('activity');
    }
}
