<?php

namespace App\Http\Controllers;

use App\Models\Seguidor;
use Illuminate\Http\Request;

class SeguidorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $seguidor = Seguidor::get();
        return response()->json($seguidor, 200, [], JSON_UNESCAPED_UNICODE|JSON_INVALID_UTF8_IGNORE);
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
        $seguidor = new Seguidor();
        $seguidor = $seguidor->fill($request->all());

        if ($seguidor->save()) {
            return response()->json($seguidor, 201, [], JSON_UNESCAPED_UNICODE|JSON_INVALID_UTF8_IGNORE);
        } else {
            return response()->json(['erro'=>'Erro ao salvar'], 401);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Seguidor  $seguidor
     * @return \Illuminate\Http\Response
     */
    public function show(Seguidor $seguidor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Seguidor  $seguidor
     * @return \Illuminate\Http\Response
     */
    public function edit(Seguidor $seguidor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Seguidor  $seguidor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Seguidor $seguidor)
    {
        $seguidor->fill($request->all());

        if ($seguidor->save()) {
            return response()->json($seguidor, 202, [], JSON_UNESCAPED_UNICODE|JSON_INVALID_UTF8_IGNORE);
        } else {
            return response()->json(['erro'=>'Erro ao editar'], 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Seguidor  $seguidor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Seguidor $seguidor)
    {
        if ($seguidor->delete()) {
            return response()->json(['mensagem'=>'sucesso ao excluir'], 202);
        } else {
            return response()->json(['erro'=>'Erro ao excluir'], 401);
        }
    }
}