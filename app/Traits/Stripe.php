<?php

namespace App\Traits;

use App\Models\PromotionCode;
use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Exceptions\CustomerAlreadyCreated;
use Stripe\Collection;
use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;

/**
 * Trait Stripe
 */
trait Stripe
{
    /**
     * @var StripeClient|null
     */
    protected $stripe = null;

    /**
     * @param  null  $key
     */
    protected function getStripeClient($key = null): StripeClient
    {

        if ($this->stripe && $this->stripe instanceof StripeClient) {

            return $this->stripe;

        }

        if (! $key) {

            $key = env('STRIPE_SECRET');

        }

        \Stripe\Stripe::setApiKey($key);

        $this->stripe = new StripeClient($key);

        return $this->stripe;

    }

    /**
     * @throws ApiErrorException
     */
    protected function getProducts(array $opts = []): \Illuminate\Support\Collection
    {

        $products = $this->getStripeClient()->products->all($opts);

        $collect = collect();

        foreach ($products as $product) {

            $collect->push($product);

        }

        return $collect;

    }

    /**
     * @throws ApiErrorException
     */
    protected function getStripePlans(array $opts = []): Collection
    {

        return $this->getStripeClient()->plans->all($opts);

    }

    /**
     * @return mixed
     *
     * @throws ApiErrorException
     */
    protected function createOrUpdateStripeCustomer($user)
    {

        $stripe = new StripeClient(

            env('STRIPE_SECRET')

        );

        $stripeCustomers = $stripe->customers->all([

            'limit' => 1,

            'email' => $user->email,

        ]);

        if ($stripeCustomers->count() == 0) {

            // Create new user.

            $options = $this->getStripeCustomerOptions($user);

            try {

                $stripeCustomer = $user->createAsStripeCustomer($options);

                Log::debug('Stripe user data: '.json_encode($stripeCustomer));

            } catch (CustomerAlreadyCreated $e) {

                Log::error($e->getMessage());

            }

        } elseif ($stripeCustomers->first()->id != $user->stripe_id) {

            $user->stripe_id = $stripeCustomers->first()->id;

            if ($user->save()) {

                Log::info("Update user stripe id: {$user->stripe_id}");

            } else {

                Log::error('Error when update user stripe id !');

                Log::debug("User id: {$user->id}");

            }

        } else {

            $options = $this->getStripeCustomerOptions($user);

            $this->updateStripeCustomer($user, $options);

        }

        return $user;

    }

    /**
     * @param  array  $options
     */
    protected function updateStripeCustomer($user, $options = [])
    {

        $user->updateStripeCustomer($options);

    }

    protected function getStripeCustomerOptions($user): array
    {

        $defaultAddressLine = '58 Hà Bá Tường, P.12, Q. Tân Bình, HCM, Vietnam';

        $options = [

            'email' => $user->email,

        ];

        if ($user->full_name) {
            $options['name'] = $user->full_name;
        }

        if ($user->phone) {
            $options['phone'] = $user->phone;
        }

        if ($user->about_me) {
            $options['description'] = $user->about_me;
        }

        $meta = [];

        if ($user->status) {
            $meta['birthday'] = $user->status;
        }

        if ($user->birthday) {
            $meta['birthday'] = $user->birthday;
        }

        if ($user->avatar) {
            $meta['avatar'] = $user->avatar;
        }

        if (count($meta) > 0) {
            $options['metadata'] = $meta;
        }

        $address = [];

        if ($user->province) {
            $address['city'] = $user->province;
        }

        if ($user->district) {
            $address['state'] = $user->district;
        }

        if (! empty($user->address) && $user->address) {
            $address['line1'] = $user->address;
        } else {
            $address['line1'] = $defaultAddressLine;
        }

        if (count($address) > 0) {
            $options['address'] = $address;
        }

        $shipping = [];

        if (! empty($user->address) && $user->address) {
            $shipping['address']['line1'] = $user->address;
        } else {
            $shipping['address']['line1'] = $defaultAddressLine;
        }

        if ($user->full_name) {
            $shipping['name'] = $user->full_name;
        }

        if ($user->phone) {
            $shipping['phone'] = $user->phone;
        }

        $shippingFilter = array_filter($shipping);

        if (count($shipping) == count($shippingFilter) && count($shipping) > 0) {
            $options['shipping'] = $shipping;
        }

        // Yeah, log options

        Log::info('User options submit to Stripe: '.json_encode($options));

        return $options;

    }

    /**
     * @throws ApiErrorException
     */
    protected function createPromotionCode(PromotionCode $promotionCode)
    {

        Log::debug('Promotion data '.json_encode($promotionCode));

        if ($couponId = $promotionCode->coupon->stripe_id) {

            $data = $promotionCode->only([

                'code',

                'expires_at',

                'max_redemptions',

            ]);

            $data['active'] = $promotionCode->active ? 'true' : 'false';

            $data['coupon'] = $couponId;

            $data['expires_at'] = $data['expires_at'] ? strtotime($data['expires_at']) : null;

            if (! $data['expires_at']) {

                unset($data['expires_at']);

            }

            if ($promotionCode->user && $promotionCode->user->stripe_id) {

                $data['customer'] = $promotionCode->user->stripe_id;

            }

            $this->getStripeClient();

            $res = $this->stripe->promotionCodes->create($data);

            PromotionCode::withoutEvents(function () use ($promotionCode, $res) {

                PromotionCode::findOrFail($promotionCode->id)->update([

                    'stripe_id' => $res->id,

                ]);

            });

            Log::info('Promotion code have been updated !');

            Log::debug('Stripe promotion code: '.json_encode($res));

        } else {

            Log::error('Coupon have not sync with Stripe !');

        }

    }
}
