<?php

namespace App\Providers;

use App\Events\AlertCreated;
use App\Events\RecordCreated;
use App\Events\ClosedAlertEvent;
use App\Events\ForwardingCreated;
use App\Events\SubGroupCreatedEvent;
use App\Events\UserAssignToCaseEvent;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Mail\Events\MessageSent;
use App\Listeners\SubGroupCreatedListener;
use Illuminate\Mail\Events\MessageSending;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        RecordCreated::class => [
            \App\Listeners\NotifyChildWithoutStudyingListener::class,           
        ],
        AlertCreated::class => [
            \App\Listeners\NotifyGovOfficesDelayVaccinesListener::class,           
        ],
        ForwardingCreated::class => [
            \App\Listeners\NotifyGovernmentOfficeListener::class,
        ],
        ClosedAlertEvent::class => [
            \App\Listeners\NotifyCoordinatorListener::class,
        ],
        UserAssignToCaseEvent::class => [
            \App\Listeners\NotifyUserAssignToCaseListener::class,
        ],
        SubGroupCreatedEvent::class => [
            SubGroupCreatedListener::class,
        ],
        MessageSending::class => [
            \App\Listeners\LogSendingMessage::class,
        ],
        MessageSent::class => [
            \App\Listeners\LogSentMessage::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
