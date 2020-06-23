<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductRatingController extends Controller
{
    public function __construct()
    {
    	$this->middleware('customer');
    }
}
