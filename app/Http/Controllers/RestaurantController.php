<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Restaurant;

class RestaurantController extends Controller
{
    public function show($id)
    {
        $restaurant = Restaurant::findOrFail($id);
        
        return view('restaurants.show', compact('restaurant'));
    }
}
