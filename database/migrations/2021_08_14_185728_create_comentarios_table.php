<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComentariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comentarios', function (Blueprint $table) {
            $table->increments('id');
            $table->string('descricao', 150);
            $table->datetime('data_comentario');
            $table->timestamps();

            //FK comentario com usuario
            $table->unsignedInteger('cod_usuario');
            $table->foreign('cod_usuario')->references("id")->on("usuarios")->onDelete("cascade");
            
            //FK comentario com postagem
            $table->unsignedInteger('cod_postagem');
            $table->foreign('cod_postagem')->references("id")->on("postagems")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comentarios');
    }
}
