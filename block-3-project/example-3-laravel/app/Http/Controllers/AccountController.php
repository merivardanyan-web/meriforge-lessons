<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        // Միայն իր պատվերները՝ կապի միջոցով, ոչ Order::all()-ից ֆիլտրով
        $orders = $request->user()->orders()
            ->withSum('items as units', 'quantity')              // մեկ հարցում, ոչ N+1
            ->latest()
            ->get();

        return view('account.index', ['user' => $request->user(), 'orders' => $orders]);
    }
}
