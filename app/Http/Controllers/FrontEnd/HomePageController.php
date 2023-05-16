<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomePageController extends Controller
{
    public function index()
    {
        return view('layout_home.master');
    }
    public function login()
    {
        return view('frontend.login');
    }
}
