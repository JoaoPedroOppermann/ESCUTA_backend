<?php

namespace App\Http\Controllers;

use App\Models\Postagem;
use DateTime;
use Illuminate\Http\Request;

class PostagemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $postagem = Postagem::get();
        return response()->json($postagem, 200, [], JSON_UNESCAPED_UNICODE|JSON_INVALID_UTF8_IGNORE);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validando campos obrigatórios
        $validator = validator()->make(request()->all(), [
                    "titulo"=>"required|string",
                    "descricao"=>"required|string",
                    "audio"=>"required|mimes:mp3,mp4,oog,m4a",
                    "cod_usuario"=>"required|exists:users,id|integer",
                ], [
                    'titulo.required' => 'Preencha o campo Titulo',
                    'descricao.required' => 'Preencha o campo Descricao',
                    'audio.required' => 'Preencha o campo Audio',
                    'audio.mimes' => 'Preencha o campo Audio com um arquivo de audio valido (mp3, mp4, oog, m4a)',
                    'cod_usuario.required' => 'Preencha o campo cod_usuario',
                    'cod_usuario.exists' => "Codigo de usuario inexistente",
                    'cod_usuario.integer' => 'O campo cod_usuario precisa se um numero inteiro',
                ]);

        if ($validator->fails()) {
            return response()->json([
                        "status"=>false,
                        "errors"=>$validator->errors()
                    ], 500);
        }

        $postagem = new Postagem();
        $postagem = $postagem->fill($request->all());
        $pasta = public_path('\audio');

        // Criando pasta fotos senão existir
        if (!file_exists($pasta)) {
            mkdir($pasta, 0777, true);
        }
        if ($request->hasFile('audio')) {
            $date = new DateTime();
            $audio = $request->file('audio');
            $nomeAudio = $audio->getClientOriginalName();
            $nomeAudio = $date->getTimestamp().$nomeAudio;
            $audio->move($pasta, $nomeAudio);
            $postagem->audio = $nomeAudio;
        }

        if ($postagem->save()) {
            return response()->json($postagem, 201, [], JSON_UNESCAPED_UNICODE|JSON_INVALID_UTF8_IGNORE);
        } else {
            return response()->json(['erro'=>'Erro ao salvar'], 401);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Postagem  $postagem
     * @return \Illuminate\Http\Response
     */
    public function show(Postagem $postagem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Postagem  $postagem
     * @return \Illuminate\Http\Response
     */
    public function edit(Postagem $postagem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Postagem  $postagem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Postagem $postagem)
    {
        $postagem->fill($request->all());

        if ($postagem->save()) {
            return response()->json($postagem, 202, [], JSON_UNESCAPED_UNICODE|JSON_INVALID_UTF8_IGNORE);
        } else {
            return response()->json(['erro'=>'Erro ao editar'], 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Postagem  $postagem
     * @return \Illuminate\Http\Response
     */
    public function destroy(Postagem $postagem)
    {
        $pasta = public_path('\audio');
        $nomeAudio = $postagem->audio;
        $caminho =  $pasta . '\\' . $nomeAudio;

        if (file_exists($pasta)) {
            unlink($caminho);
        }

        if ($postagem->delete()) {
            return response()->json(['mensagem'=>'sucesso ao excluir'], 202);
        } else {
            return response()->json(['erro'=>'Erro ao excluir'], 401);
        }
    }
}