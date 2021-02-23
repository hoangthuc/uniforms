<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Payment;

class PaymentController extends Controller
{
    public $gateway;

    public function __construct()
    {
    }

    public function index()
    {
        return view('payment');
    }

    public function charge(Request $request)
    {
    }
}
