<?php
namespace App\Mail\Auth;

use App\Models\User;
use App\Models\Accession;
use App\Services\UserService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Http\Resources\UserResource;
use Illuminate\Queue\SerializesModels;

class RecoverPassword extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        try {
            $url = env('URL_EMAIL_SERVICE');

            $link = $url . '/reset-password/token=' . $this->user->token . '&email=' . $this->user->email;
            $params = [
                'name' => $this->user->name,
                'url' => $link
            ];

            return  $this->view('email.auth.recover-password')
                ->with($params)
                ->subject('BAV | Recuperar Senha');

        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
}
