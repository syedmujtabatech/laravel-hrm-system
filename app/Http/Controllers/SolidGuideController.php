<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SolidGuideController extends Controller
{
    public function index()
    {
        return view('solid.guide');
    }
}
