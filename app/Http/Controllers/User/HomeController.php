<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.

     *

     * @return void
     */
    public function __construct()
    {

        $this->middleware('guest')->except('logout');

    }

    /**
     * Show the application dashboard.

     *

     * @return Renderable
     */
    public function index()
    {

        return view('user.home');

    }
}
