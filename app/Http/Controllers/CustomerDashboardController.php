<?php

namespace App\Http\Controllers;

class CustomerDashboardController extends Controller
{
    public function index()
    {
        return view('customer.dashboard');
    }
} 