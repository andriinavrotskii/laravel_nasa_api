<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DefaultController extends Controller
{
    public function index()
    {
        return response()->json(['hello' => 'world!'], 200);
    }
}
