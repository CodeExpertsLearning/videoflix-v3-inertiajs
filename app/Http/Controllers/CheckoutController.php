<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    private $user;

    public function __construct()
    {
        $this->user = auth()->user();
    }

    public function checkout()
    {
        return inertia('Checkout', [
            'stripe_intent' => $this->user->createSetupIntent(),
            'cashier_key'   => config('cashier.key'),
        ]);
    }

    public function charge(Request $request)
    {
        if (!$this->user->subscribed('default')) {
            $this->user->newSubscription('default', 'price_1R9wUuLVUytZ8PTCEJl3PkVS')->create($request->paymentMethod);
        }

        return redirect()->route('my-content');
    }
}
