<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostagemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('postagems', function (Blueprint $table) {
            $table->increments('id');
            $table->string('titulo', 50);
            $table->string('descricao', 255);
            $table->char('comentario', 1);
            $table->char('curtidas', 1);
            $table->string('audio', 255);
            $table->datetime('data_postagem');
            $table->timestamps();
            //FK usuario da postagem
            $table->unsignedInteger('cod_usuario');
            $table->foreign('cod_usuario')->references("id")->on("usuarios")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('postagems');
    }
}
