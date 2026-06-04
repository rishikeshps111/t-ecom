<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FrontendController extends Controller
{

    public function home(Request $request)
    {
        return view('frontend.home');
    }

    public function contact(Request $request)
    {
        return view('frontend.contact');
    }

    public function serviceOne(Request $request)
    {
        return view('frontend.service-one');
    }

    public function serviceTwo(Request $request)
    {
        return view('frontend.service-two');
    }

    public function serviceThree(Request $request)
    {
        return view('frontend.service-three');
    }

    public function totalNet(Request $request)
    {
        return view('frontend.total-net');
    }
}
