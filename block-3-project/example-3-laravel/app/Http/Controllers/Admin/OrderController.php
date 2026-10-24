<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::withCount('items')
            ->withSum('items as units', 'quantity')
            ->latest()
            ->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    public function update(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(array_keys(Order::STATUS))],
        ]);

        $order->status = $data['status'];                // status-ը $fillable-ում չէ — ուղիղ վերագրում
        $order->save();

        return back()->with('success', 'Կարգավիճակը թարմացվեց՝ ' . $order->number());
    }
}
