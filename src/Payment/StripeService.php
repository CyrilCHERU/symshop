<?php

namespace App\Payment;

use Stripe\Exception\CardException;

class StripeService
{
    protected $key;

    public function __construct(string $key)
    {
        $this->key = $key;
    }

    public function processCharge(int $total, string $stripeToken, string $description)
    {
        \Stripe\Stripe::setApiKey($this->key);

        try {
            // `source` is obtained with Stripe.js; 
            // see https://stripe.com/docs/payments/cards/collecting/web#create-token
            $charge = \Stripe\Charge::create([
                'amount' => $total,
                'currency' => 'eur',
                'source' => $stripeToken,
                'description' => $description,
            ]);
            return $charge['paid'];
        } catch (CardException $e) {
            return false;
        }
    }
}
