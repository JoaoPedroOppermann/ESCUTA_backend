<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use ImageResize;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usuarios = Usuario::get();
        return response()->json($usuarios, 200, [], JSON_UNESCAPED_UNICODE|JSON_INVALID_UTF8_IGNORE);
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
        $this->validate($request, ['image.*', 'mimes:jpeg, jpg, gif, png']);
        $pasta = public_path('\fotos');
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $mini = ImageResize::make($foto->path());
            $nomeArquivo = $request->file('foto')->getClientOriginalName();
            //$path = $pasta . '\' . $nomeArquivo;
            if (!$mini->resize(500, 500, function ($constriant) {
                $constriant->aspectRatio();
            })->save($pasta . '\\' . $nomeArquivo)) {
                $nomeArquivo = "vazio.jpg";
            }
        } else {
            $nomeArquivo = 'vazio.jpg';
        }
        $usuario = new Usuario();
        $usuario = $usuario->fill($request->all());
        $usuario['foto'] = $nomeArquivo;

        if ($usuario->save()) {
            return response()->json($usuario, 201, [], JSON_UNESCAPED_UNICODE|JSON_INVALID_UTF8_IGNORE);
        } else {
            return response()->json(['erro'=>'Erro ao salvar'], 401);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function show(Usuario $usuario)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function edit(Usuario $usuario)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Usuario $usuario)
    {
        $usuario->fill($request->all());

        if ($usuario->save()) {
            return response()->json($usuario, 202, [], JSON_UNESCAPED_UNICODE|JSON_INVALID_UTF8_IGNORE);
        } else {
            return response()->json(['erro'=>'Erro ao editar'], 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function destroy(Usuario $usuario)
    {
        if ($usuario->delete()) {
            return response()->json(['mensagem'=>'sucesso ao excluir'], 202);
        } else {
            return response()->json(['erro'=>'Erro ao excluir'], 401);
        }
    }
}
