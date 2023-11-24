<?php

namespace App\Mail\Accession;

use App\Http\Resources\UserResource;
use App\Models\Accession;
use App\Services\UserService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ErrataGestor extends Mailable
{
    use Queueable, SerializesModels;

    protected $accession;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Accession $accession)
    {
        $this->accession = $accession;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $userService = app()->make(UserService::class);
        $user = new UserResource($userService->find($this->accession->gestor_politico_id));

        $userSerialize = $user->jsonSerialize();
        $accessionId = $this->accession->uuid;
        $userId = $user->uuid;

        $url = env('URL_EMAIL_SERVICE');
        $url .= "/accession/$accessionId/confirm-external-user-password/$userId";

        $params = [
            'pronoun' => $userSerialize['pronoun']['name'],
            'name' => $userSerialize['name'],
            'url' => $url
        ];

        return $this->view('email.accession.errata-gestor')
            ->with($params)
            ->subject('ERRATA | BAV | Solicitação de adesão PENDENTE - Gestor');
    }
}
