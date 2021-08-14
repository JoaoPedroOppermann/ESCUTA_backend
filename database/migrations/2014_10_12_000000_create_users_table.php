<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome', 100);
            $table->string('sobrenome', 100);
            $table->string('usuario', 100)->unique();
            $table->string('email', 100);
            $table->string('password', 100);
            $table->datetime('data_nascimento');
            $table->char('ativo', 1)->default('V');
            $table->char('genero', 2);
            $table->string('biografia', 255);
            $table->char('termos', 1);
            $table->char('admin', 1)->default('F');
            $table->string('telefone', 20);
            $table->string('foto', 255);
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}