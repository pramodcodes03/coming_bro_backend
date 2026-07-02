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
            // The dropdown sends short slugs (placed, cancelled, …) but the DB
            // stores human status strings ("Ride Placed", "Ride Canceled",
            // "Completed", …) that vary. Match every stored spelling that means
            // the chosen state, case-insensitively.
            $aliases = $this->statusAliases($status);
            $query->where(function ($q) use ($aliases) {
                foreach ($aliases as $alias) {
                    $q->orWhereRaw('LOWER(TRIM(status)) = ?', [$alias]);
                }
            });
        }

        $orders = $query->orderBy('id', 'desc')->paginate(15)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Map a filter slug to every status string that state may be stored as
     * (lowercased/trimmed for a case-insensitive match). Mirrors the buckets
     * used across the app so "Cancelled" catches "Ride Canceled", etc.
     *
     * @return array<int, string>
     */
    private function statusAliases(string $slug): array
    {
        $map = [
            'placed'      => ['ride placed', 'placed'],
            'accepted'    => ['ride accepted', 'accepted'],
            'arriving'    => ['arriving', 'ride arriving'],
            'arrived'     => ['arrived', 'ride arrived'],
            'in_progress' => ['in progress', 'in_progress', 'ride in progress', 'ride active', 'started', 'ride started'],
            'ongoing'     => ['ongoing', 'ride ongoing', 'on trip', 'on going'],
            'completed'   => ['completed', 'ride completed', 'ride end', 'end ride', 'finished'],
            'cancelled'   => ['cancelled', 'canceled', 'ride cancelled', 'ride canceled', 'rejected'],
        ];

        // Fall back to matching the raw value itself if it's not a known slug.
        return $map[strtolower($slug)] ?? [strtolower(trim($slug))];
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
