<?php
 
namespace App\Listeners;
 
use Laravel\Cashier\Events\WebhookReceived;
use Illuminate\Support\Facades\Log;
 
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