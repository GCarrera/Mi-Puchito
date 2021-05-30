<?php

namespace App\Listeners;

use App\Events\NotificacionVentaStatusChangedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class VentaStatusListener
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
     * @param  NotificacionVentaStatusChangedEvent  $event
     * @return void
     */
    public function handle(NotificacionVentaStatusChangedEvent $event)
    {
        //
    }
}
