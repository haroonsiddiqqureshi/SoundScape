<?php

namespace App\Http\Controllers\User;

use App\Models\Concert;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ConcertController extends Controller
{
    public function index()
    {
        $concerts = Concert::all();
        return response()->json($concerts);
    }
}
