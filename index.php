<?php
require 'vendor/autoload.php';

if(isset($_POST['authkey'])&&($_POST['authkey'] == 'abc')) {
    $stripe = new \Stripe\StripeClient('sk_test_51MniN0SFVfT4m6KTJM4dNUGu4gAz6iGPA71KIUz33nRqRLt3yBnpgBd8UlM3xYmFoRl4lZIqUkGjaIfwiXoGuvfB002XeHFZBV');

    $customer = $stripe->customers->create([
        'name' => "Subham",
        'address' => [
            'line1' => 'Demo address',
            'postal_code' => '324005',
            'city' => 'Durgapur',
            'state' => 'WB',
            'country' => 'India'
        ]
    ]);
    $ephemeralKey = $stripe->ephemeralKeys->create([
        'customer' => $customer->id,
    ], [
        'stripe_version' => '2022-08-01',
    ]);
    $paymentIntent = $stripe->paymentIntents->create([
        'amount' => 1099,
        'currency' => 'usd',
        'description' => 'Payment for contract',
        'customer' => $customer->id,
        'automatic_payment_methods' => [
            'enabled' => 'true',
        ],
    ]);

    echo json_encode(
        [
            'paymentIntent' => $paymentIntent->client_secret,
            'ephemeralKey' => $ephemeralKey->secret,
            'customer' => $customer->id,
            'publishableKey' => 'pk_test_51MniN0SFVfT4m6KT3aHZNKokEB9BDXCL5tAJhDn50ZL2KTLU2WwHVYncwyyQ4nKub7q6Rvw6JopHVKopDz8IXYb900Z42MSoYR'
        ]
    );
    http_response_code(200);
}else{
    echo "Error in auth";
}