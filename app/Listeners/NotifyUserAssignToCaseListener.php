<?php

namespace App\Listeners;

use Carbon\Carbon;
use App\Models\ClosedAlert;
use App\Events\ClosedAlertEvent;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\Events\UserAssignToCaseEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyUserAssignToCaseListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(UserAssignToCaseEvent $event)
    {
        $closedAlert = $event->caseStep;        

        $link = url('') . '/login';
        $recipient = "";

        $data = [
            'title' => 'BAV - Novo caso atribuído',
            'content' => '<p>Olá, um novo caso foiatribuído a você BAV. Acesse sua conta na BAV para iniciar a análise</p>',
            'link' => $link
        ];

        $content = '<p>Olá, um novo caso foi atribuído a você. Acesse sua conta na BAV para iniciar a análise</p>';
        $footerContent = '';
        Mail::send('email.default', [
            'content' => $content,
            'footerContent' => $footerContent
        ], function($message) use ($recipient) {
            $message->from(env('MAIL_ADDRESS_FROM'), 'BAV');
            $message->subject('BAV - Encaminhamento de alerta');
            $message->to($recipient);
        });        
    }
}
