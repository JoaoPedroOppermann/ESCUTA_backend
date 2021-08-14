<?php

namespace App\Http\Controllers;

use App\Models\Postagem;
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
        $postagem = new Postagem();
        $postagem = $postagem->fill($request->all());

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
        if ($postagem->delete()) {
            return response()->json(['mensagem'=>'sucesso ao excluir'], 202);
        } else {
            return response()->json(['erro'=>'Erro ao excluir'], 401);
        }
    }
}
