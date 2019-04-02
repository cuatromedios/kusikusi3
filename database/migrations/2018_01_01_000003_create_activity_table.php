<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateActivityTable
 */
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
            $table->uuid('user_id')->index();
            $table->uuid('entity_id')->index();
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
