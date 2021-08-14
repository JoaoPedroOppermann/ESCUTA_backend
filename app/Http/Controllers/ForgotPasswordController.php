<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB as FacadesDB;
use App\Mail\SendMailClient;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    public function reqForgotPassword(Request $request)
    {
        if (!$this->validEmail($request->email)) {
            return response()->json([
                'error' => 'Email não encontrado'
            ], 404);
        } else {
            $user = User::where('email', $request->email)->first();
            $this->sendEmail($user);
            return response()->json([
                'mensagem' => 'Verifique seu email!'
            ], 200);
        }
    }


    public function sendEmail($user)
    {
        $token = $this->createToken($user->email);
        $blade = 'emails.layout_client';
        Mail::to($user->email)->send(new SendMailClient($user, $token, $blade));
    }

    public function validEmail($email)
    {
        return !!User::where('email', $email)->first();
    }

    public function createToken($email)
    {
        $isToken = DB::table('password_resets')->where('email', $email)->first();

        if ($isToken) {
            return $isToken->token;
        }

        $token = Str::random(80);
        $this->saveToken($token, $email);
        return $token;
    }

    public function saveToken($token, $email)
    {
        FacadesDB::table('password_resets')->insert([
            'email' => $email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);
    }

    public function updatePassword(Request $request)
    {
        return $this->validateToken($request)->count() > 0 ? $this->changePassword($request) : $this->noToken();
    }

    private function validateToken($request)
    {
        return DB::table('password_resets')->where([
            'token' => $request->token
        ]);
    }

    private function noToken()
    {
        return response()->json([
          'error' => 'Email ou token não existente.'
        ], 422);
    }

    private function changePassword($request)
    {
        $user = User::whereEmail($request->email)->first();
        $user->update([
          'password'=>bcrypt($request->password)
        ]);
        $this->validateToken($request)->delete();
        return response()->json([
          'mensagem' => 'Senha alterada com sucesso'
        ], 201);
    }
}