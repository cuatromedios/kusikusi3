<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\User;

class CreateUserTable extends Migration
{
    /**
     * User table migration
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->char('entity_id', 36)->unique()->primary();
            $table->string('name', 100)->index();
            $table->string('email', 100);
            $table->string('password', 255);
            $table->enum('profile', [User::PROFILE_ADMIN, User::PROFILE_EDITOR, User::PROFILE_USER])->index()->default(User::PROFILE_USER);
        });
        Schema::create('authtokens', function (Blueprint $table) {
            $table->char('token', 128)->unique()->primary();
            $table->char('entity_id', 36)->index();
            $table->char('created_ip', 45);
            $table->char('updated_ip', 45);
            $table->timestamps();
        });
        Schema::create('permissions', function (Blueprint $table) {
            $table->char('user_id', 36)->index();
            $table->char('entity_id', 36)->index();
            $table->enum('get',     ['none', 'own', 'any']);
            $table->enum('post',    ['none', 'own', 'any']);
            $table->enum('patch',   ['none', 'own', 'any']);
            $table->enum('delete',  ['none', 'own', 'any']);
            $table->primary(['user_id', 'entity_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('authtokens');
        Schema::dropIfExists('permissions');
    }
}
