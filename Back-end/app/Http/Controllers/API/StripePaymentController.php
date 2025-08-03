<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Exception\ApiErrorException;

class StripePaymentHandlerController extends Controller
{
    public function makePayment(Request $request)
    {
        // // Set your secret key
        // Stripe::setApiKey(env('STRIPE_SECRET'));

        // // dd($request->amount);
        // try {
        //     // Create a PaymentIntent to charge a customer
        //     $paymentIntent = PaymentIntent::create([
        //         'amount' => $request->amount, // Example amount in cents
        //         'currency' => 'usd',
        //         'payment_method_types' => ['card'],
        //         'description' => 'Example Payment',
        //     ]);

        //     // Return client secret to frontend
        //     return response()->json(['clientSecret' => $paymentIntent->client_secret]);
        // } catch (ApiErrorException $e) {
        //     // Handle error
        //     return response()->json(['error' => $e->getMessage()], 500);
        // }
        return 'kk';
    }
}
