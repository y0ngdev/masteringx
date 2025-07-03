<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Services\SettingsManager;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class IndexController extends Controller
{
    public function index(): Response
    {

        return Inertia::render('Welcome', [
            'plan' => Plan::first()
        ]);
    }

    public function buy(Request $request)
    {
        $planId = $request->input('plan_id');

    }
}
