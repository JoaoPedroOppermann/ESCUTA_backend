<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeguidorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seguidors', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            //FK seguidor com usuario
            $table->unsignedInteger('cod_usuario_seguidor');
            $table->foreign('cod_usuario_seguidor')->references("id")->on("users")->onDelete("cascade");

            //FK usuario perfil
            $table->unsignedInteger('cod_usuario_perfil');
            $table->foreign('cod_usuario_perfil')->references("id")->on("users")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seguidors');
    }
}