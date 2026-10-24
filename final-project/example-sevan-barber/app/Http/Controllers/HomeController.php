<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\TimeSlot;
use App\Models\User;

class HomeController extends Controller
{
    public function index()
    {
        return view('home', [
            'services'  => Service::active()->orderBy('price')->get(),
            'staff'     => User::staff()->get(['id', 'name']),
            'freeToday' => TimeSlot::free()->upcoming()->onDate(today())->count(),
        ]);
    }
}
