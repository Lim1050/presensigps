<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CashbonController extends Controller
{
    public function CashbonIndex()
    {
        return view('cashbon.cashbon_index');
    }
}
