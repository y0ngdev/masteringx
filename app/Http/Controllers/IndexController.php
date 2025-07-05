<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class IndexController extends Controller
{
    public function index(): Response
    {
//        dd( Plan::first());
        return Inertia::render('Welcome', [
            'plan' => Plan::first(),
            'testimonials' => Testimonial::published()->orderBy('order')->get()->all(),
        ]);
    }

    public function buy(Request $request)
    {
        Stripe::setApiKey(config('payments.drivers.stripe.secret_key'));

        $priceId = $request->input('priceId');


        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price' => $priceId,
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('buy.success'),
//            'cancel_url' =>route('home'),
        ]);

    }
}
