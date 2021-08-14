<?php

namespace App\Http\Controllers;

use App\Models\User;
use DateTime;
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
        $usuarios = User::get();
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
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $usuario
     * @return \Illuminate\Http\Response
     */
    public function show(User $usuario)
    {
        $usuario = User::find($usuario);
        return $usuario;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $usuario
     * @return \Illuminate\Http\Response
     */
    public function edit(User $usuario)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $usuario
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $usuario)
    {
        $usuario = $usuario->fill($request->all());
        if ($usuario->save()) {
            return response()->json($usuario, 202, [], JSON_UNESCAPED_UNICODE|JSON_INVALID_UTF8_IGNORE);
        } else {
            return response()->json(['erro'=>'Erro ao editar'], 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $usuario
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $usuario)
    {
        if ($usuario->delete()) {
            return response()->json(['mensagem'=>'sucesso ao excluir'], 202);
        } else {
            return response()->json(['erro'=>'Erro ao excluir'], 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $usuario
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function editImage(Request $request, User $usuario)
    {
        $usuario = $usuario->fill($request->all());

        // Validando campos obrigatórios
        $validator = validator()->make(request()->all(), [
                    "foto"=>"required|image|mimes:jpeg,jpg,png",
                ], [
                    'foto.required' => 'Preencha o campo foto',
                    'foto.image' => 'Preencha o campo foto com um arquivo de imagem',
                    'foto.mimes' => 'Preencha o campo foto com um arquivo de imagem valido (jpeg, jpg, png)',
                ]);

        if ($validator->fails()) {
            return response()->json([
                        "status"=>false,
                        "errors"=>$validator->errors()
                    ], 500);
        }

        $pasta = public_path('\fotos');

        // Criando pasta fotos senão existir
        if (!file_exists($pasta)) {
            mkdir($pasta, 0777, true);
        }

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $mini = ImageResize::make($foto->path());
            $nomeArquivo = $request->file('foto')->getClientOriginalName();
            if (!$mini->resize(500, 500, function ($constriant) {
                $constriant->aspectRatio();
            })->save($pasta . '\\' . $nomeArquivo)) {
                $nomeArquivo = "vazio.jpg";
            }
            $date = new DateTime();
            $usuario['foto'] = $date->getTimestamp().$nomeArquivo;
        }

        if ($usuario->save()) {
            return response()->json(['mensagem'=>'imagem editado com sucesso'], 202);
        } else {
            return response()->json(['erro'=>'Erro ao editar imagem'], 401);
        }
    }
}