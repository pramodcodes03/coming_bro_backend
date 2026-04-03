<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['customer', 'driver']);

        if ($search = $request->input('search')) {
            $query->where('id', $search);
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $orders = $query->orderBy('id', 'desc')->paginate(15)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with(['customer', 'driver', 'acceptedDrivers'])->findOrFail($id);

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|string|in:placed,accepted,arriving,arrived,in_progress,ongoing,completed,cancelled',
        ]);

        $order->status = $validated['status'];
        $order->save();

        return redirect()->back()
            ->with('success', 'Order status updated successfully.');
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return redirect()->route('admin.orders.index')
            ->with('success', 'Order deleted successfully.');
    }
}
