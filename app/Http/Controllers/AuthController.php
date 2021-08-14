<?php

namespace App\Http\Controllers;

use App\Models\User;
use DateTime;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Password;
use Exception;
use ImageResize;

class AuthController extends Controller
{
    public function __construct()
    {
        //
    }

    public $loginAfterSignUp = true;

    public function login(Request $request)
    {
        $validator = validator()->make(request()->all(), [
            "email"=>"required|email",
            "password"=>"required|string",
        ], [
            'email.required' => 'Preencha o campo Email',
            'password.required' => 'Preencha o campo Senha'
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status"=>false,
                "errors"=>$validator->errors()
            ], 500);
        }

        $credenciais = $request->only('email', 'password');
        $token = null;

        if (!$token = JWTAuth::attempt($credenciais, true)) {
            return response()->json([
                'status'=>false,
                'mensagem'=>'Dados inválidos'
            ]);
        }

        return response()->json([
            'status'=>true,
            'token'=>$token,
        ]);
    }

    public function register(Request $request)
    {
        // Validando campos obrigatórios
        $validator = validator()->make(request()->all(), [
            "nome"=>"required|string",
            "sobrenome"=>"required|string",
            "email"=>"required|email|unique:users",
            "usuario"=>"required|string|unique:users",
            "password"=>"required|string|min:8|max:16",
            "telefone"=>"required|string",
            "genero"=>"required|string",
            "data_nascimento"=>"required|date",
            "biografia"=>"required|string",
            "termos"=>"required|string",
        ], [
            'name.required' => 'Preencha o campo Nome',
            'sobrenome.required' => 'Preencha o campo Sobrenome',
            'email.required' => 'Preencha o campo Email',
            'email.email' => 'Preencha um Email válido',
            'email.unique' => 'Email já cadastrado',
            'usuario.required' => 'Preencha o campo Usuario',
            'usuario.unique' => 'Usuario já cadastrado',
            'password.required' => 'Preencha o campo Senha',
            'password.min' => 'Senha precisa ter mais de 8 digitos',
            'password.max' => 'Senha precisa ter no máximo 16 digitos',
            'telefone.required' => 'Preencha o campo Telefone',
            'genero.required' => 'Preencha o campo Genero',
            'data_nascimento.required' => 'Preencha o campo Data de Nascimento',
            'data_nascimento.date' => 'Preencha o campo Data de Nascimento com uma Data correta',
            'biografia.required' => 'Preencha o campo Biografia',
            'termos.required' => 'Preencha o campo Termos',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status"=>false,
                "errors"=>$validator->errors()
            ], 500);
        }

        $this->validate($request, ['image.*', 'mimes:jpeg, jpg, gif, png']);
        $pasta = public_path('\fotos');

        // Criando pasta fotos senão existir
        if (!file_exists($pasta)) {
            mkdir($pasta, 0777, true);
        }

        if ($request->hasFile('foto')) {

            $date = new DateTime();
            $foto = $request->file('foto');
            $mini = ImageResize::make($foto->path());
            $nomeArquivo = $request->file('foto')->getClientOriginalName();
            $nomeArquivo = $date->getTimestamp().$nomeArquivo;
            $foto = $request->file('foto');
            $mini = ImageResize::make($foto->path());

            if (!$mini->resize(500, 500, function ($constriant) {
                $constriant->aspectRatio();
            })->save($pasta . '\\' . $nomeArquivo)) {
                $nomeArquivo = "vazio.jpg";
            }
        } else {
            $nomeArquivo = 'vazio.jpg';
        }

        $user = new User();
        $user->nome = $request->nome;
        $user->sobrenome = $request->sobrenome;
        $user->email = $request->email;
        $user->usuario = $request->usuario;
        $user->password = bcrypt($request->password);
        $user->telefone = $request->telefone;
        $user->termos = $request->termos;
        $user->genero = $request->genero;
        $user->biografia = $request->biografia;
        $user->data_nascimento = $request->data_nascimento;
        $user['foto'] = $nomeArquivo;
        $user->save();

        if ($this->loginAfterSignUp) {
            return $this->login($request);
        }

        return response()->json([
            "status"=>true,
            "user"=>$user
        ], 202);
    }

    public function logout(Request $request)
    {
        try {
        } catch (\Throwable $th) {
            return response()->json([
                "status" => false,
                "user" => "Erro ao deslogar"
            ]);
        }
    }

    public function reset()
    {
        $credenciais = request()->validate([
            'email'=>'required|email',
            'password'=>'required|string|max:16|confirmed',
            'token'=>'required|string'
        ]);

        $email_password_status = Password::reset($credenciais, function ($user, $password) {
            $user->password = $password;
            $user->save();
        });

        if ($email_password_status == Password::INVALID_TOKEN) {
            return $this->responsedBadRequest();
        }
    }
    public function validateToken()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return response()->json(['status' => 'Token invalido']);
            } elseif ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return response()->json(['status' => 'Token expirado']);
            } else {
                return response()->json(['status' => 'Token de autorização não encontrado']);
            }
        }

        return response()->json(compact('user'));
    }
}