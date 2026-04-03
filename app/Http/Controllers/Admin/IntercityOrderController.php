<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IntercityOrder;
use App\Models\Customer;
use App\Models\DriverUser;
use Illuminate\Http\Request;

class IntercityOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = IntercityOrder::query()
            ->leftJoin('customers', 'orders_intercity.user_id', '=', 'customers.id')
            ->leftJoin('driver_users', 'orders_intercity.driver_id', '=', 'driver_users.id')
            ->select('orders_intercity.*', 'customers.full_name as customer_name', 'driver_users.full_name as driver_name');

        if ($search = $request->input('search')) {
            $query->where('orders_intercity.id', $search);
        }

        if ($status = $request->input('status')) {
            $query->where('orders_intercity.status', $status);
        }

        $orders = $query->orderBy('orders_intercity.id', 'desc')->paginate(15)->withQueryString();

        return view('admin.intercity-orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = IntercityOrder::findOrFail($id);
        $order->customer = Customer::find($order->user_id);
        $order->driverUser = DriverUser::find($order->driver_id);

        return view('admin.intercity-orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = IntercityOrder::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|string|in:placed,accepted,arriving,arrived,in_progress,ongoing,completed,cancelled',
        ]);

        $order->status = $validated['status'];
        $order->save();

        return redirect()->back()
            ->with('success', 'Intercity order status updated successfully.');
    }

    public function destroy($id)
    {
        $order = IntercityOrder::findOrFail($id);
        $order->delete();

        return redirect()->route('admin.intercity-orders.index')
            ->with('success', 'Intercity order deleted successfully.');
    }
}
