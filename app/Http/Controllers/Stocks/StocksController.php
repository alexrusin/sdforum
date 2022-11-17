<?php

namespace App\Http\Controllers\Stocks;

use App\Http\Controllers\Controller;

class StocksController extends Controller
{
    public function index()
    {
        return view('stocks.stocks-chart');
    }
}
