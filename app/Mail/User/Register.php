<?php

namespace App\Mail\User;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Register extends Mailable
{
    use Queueable, SerializesModels;

    protected User $user;

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
        $userResource = new UserResource($this->user);

        $userSerialize = $userResource->jsonSerialize();

        $url = str_replace('api.', '', url('/'));

        $userId = $this->user->uuid;

        $url .= "/confirm-external-user/$userId";

        $params = [
            'pronoun' => $userSerialize['pronoun']['name'],
            'name' => $userSerialize['name'],
            'url' => $url
        ];

        return $this->view('email.user.register')
            ->with($params)
            ->subject('BAV | Pedido de adesão');
    }
}
