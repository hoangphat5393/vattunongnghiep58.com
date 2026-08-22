<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Events\WebhookReceived;

class StripeEventListener
{
    /**
     * Handle received Stripe webhooks.
     */
    public function handle(WebhookReceived $event): void
    {

        Log::info('test here 1');

        if ($event->payload['type'] === 'charge.succeeded') {

            Log::info('test here');

        }

    }
}
