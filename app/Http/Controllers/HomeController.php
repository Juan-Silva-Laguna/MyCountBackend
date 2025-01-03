<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    //
    public function validation()
    {
        return response()->json(['message' => 'Token Autorizado', 'authorize' => true]);
    }
}
