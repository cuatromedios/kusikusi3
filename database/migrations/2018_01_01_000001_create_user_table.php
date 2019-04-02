<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->uuid('id')->unique()->primary();
            $table->string('username', 100)->index();
            $table->string('name', 100)->nullable();
            $table->string('email', 100)->nullable()->index()->unique();
            $table->string('password', 255)->nullable();
            $table->enum('profile', [User::PROFILE_ADMIN, User::PROFILE_EDITOR, User::PROFILE_USER])
                  ->index()
                  ->default(User::PROFILE_USER)
            ;
        });
        Schema::create('authtokens', function (Blueprint $table) {
            $table->char('token', 128)->unique()->primary();
            $table->uuid('user_id')->index();
            $table->char('created_ip', 45)->nullable();
            $table->char('updated_ip', 45)->nullable();
            $table->dateTime('expire_at')->nullable();
            $table->timestamps();
        });
        Schema::create('permissions', function (Blueprint $table) {
            $table->uuid('user_id')->index();
            $table->uuid('entity_id')->index();
            $table->enum('read', ['none', 'own', 'any']);
            $table->enum('write', ['none', 'own', 'any']);
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
