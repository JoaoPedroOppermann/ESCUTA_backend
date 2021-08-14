<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMailClient extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.

     *
     * @return void
     */
    public function __construct(User $user, String $token, String $blade)
    {
        $this->content = $user;
        $this->token = $token;
        $this->blade = $blade;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('to@email.com')
                ->subject('Recuperação de Senha')
                ->markdown($this->blade)
                ->with([
                    'content' => $this->content,
                    'url'     => 'http://localhost:8000/api/atualizarSenha?token='. $this->token
                ]);
    }
}