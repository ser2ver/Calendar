<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except('index');
    }

    /**
     * Show the application dashboard.
     *
     * @param int $year
     * @param int $month
     * @return \Illuminate\Http\Response
     */
    public function index(int $year=0, int $month=0)
    {
        $calendar = new \App\Calendar(Auth::user());
        $calendar = $calendar->make($year, $month);
        return view('home', ['calendar' => $calendar]);
    }
}
