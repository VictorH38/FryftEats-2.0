<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            // $user = Auth::user()->with[('favorites')]->get();
            $user = Auth::user();
            
            return view('home.index', [
                'favorites' => $user->favorites
            ]);
        } else {
            return view('home.index', [
                'favorites' => collect()
            ]);
        }
    }
}
