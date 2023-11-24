<?php

namespace App\Mail\Accession;

use App\Http\Resources\UserResource;
use App\Models\Accession;
use App\Services\UserService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RejectGestor extends Mailable
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

        $params = [
            'pronoun' => $userSerialize['pronoun']['name'],
            'name' => $userSerialize['name'],
            'rejection_description' => $this->accession->rejection_description,
        ];

        return $this->view('email.accession.reject-gestor')
            ->with($params)
            ->subject('BAV | Rejeição de adesão - Gestor');
    }
}
