<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Testimonial;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;
use Stripe\Stripe;

class IndexController extends Controller
{
    public function index(): Response
    {
//        dd( Plan::first());
        return Inertia::render('Welcome', [
            'csrf_token' => csrf_token(),
            'plan' => Plan::first(),
            'testimonials' => Testimonial::published()->orderBy('order')->get()->all(),
        ]);
    }

    /**
     * @throws ApiErrorException
     */
    public function buy(Request $request)
    {
        Stripe::setApiKey(config('payments.drivers.stripe.secret_key'));

        $priceId = $request->input('priceId');
//        dd($priceId);

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price' => $priceId,
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('buy.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('homepage'),
        ]);

        return redirect($session->url);

    }

    /**
     * @throws ApiErrorException
     */
    public function success(Request $request)
    {

        $sessionId = $request->get('session_id');

        if (!$sessionId) {
            abort(400, 'Session ID missing');
        }

        Stripe::setApiKey(config('payments.drivers.stripe.secret_key'));

        $session = Session::retrieve($sessionId);


        if ($session->payment_status !== 'paid') {
            abort(400, 'Payment not completed');
        }


//        Transaction::create([
//            'user_id' => $user->id,
//            'checkout_session_id' => $session['id'],
//            'payment_intent_id' => $session['payment_intent'],
//            'customer_id' => $session['customer'],
//            'amount' => $session['amount_total'] / 100,
//            'currency' => strtoupper($session['currency']),
//            'status' => 'successful',
//            'receipt_url' => $session['payment_intent']['charges']['data'][0]['receipt_url'] ?? null,
//            'paid_at' => now(),
//            'metadata' => $session['metadata'] ?? [],
//        ]);


        // Get email and other info from session
        $email = $session->customer_details->email ?? null;
        $name = $session->customer_details->name ?? 'New User';

        // Register user (or log them in)
        $user = User::firstOrCreate(
            ['email' => $email],
            ['name' => $name, 'password' => bcrypt(Str::random(16))] // Or send them a password setup link
        );

        Auth::login($user);
//Todo log transaction



        Password::sendResetLink(
            $request->only('email')
        );

        return redirect(route('dashboard'))->with('toast', [
            'type' => 'success',
            'message' => 'Welcome! A link to reset your password has been sent',
        ]);
    }
}
