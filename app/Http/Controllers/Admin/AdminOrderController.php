<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminOrderController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'string', 'max:60'],
            'from' => ['nullable', 'date_format:Y-m-d'],
            'to' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:from'],
        ]);

        $query = Order::query()
            ->with('items')
            ->orderByDesc('created_at');

        if (! empty($filters['q'])) {
            $term = trim($filters['q']);
            $query->where(function ($builder) use ($term) {
                $builder->where('order_number', 'like', '%'.$term.'%')
                    ->orWhere('customer_name', 'like', '%'.$term.'%')
                    ->orWhere('customer_email', 'like', '%'.$term.'%')
                    ->orWhere('customer_phone', 'like', '%'.$term.'%')
                    ->orWhereHas('items', fn ($items) => $items->where('product_name', 'like', '%'.$term.'%'));
            });
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['from'])) {
            $query->whereDate('created_at', '>=', $filters['from']);
        }

        if (! empty($filters['to'])) {
            $query->whereDate('created_at', '<=', $filters['to']);
        }

        $orders = $query->paginate(15)->withQueryString();
        $statuses = Order::query()->select('status')->distinct()->orderBy('status')->pluck('status');
        $stats = [
            'total' => Order::query()->count(),
            'today' => Order::query()->whereDate('created_at', today())->count(),
            'attention' => Order::query()
                ->whereIn('status', ['pending', 'demo_order_pending', 'pending_pharmacist_review'])
                ->count(),
            'value' => Order::query()->sum('total'),
        ];

        return view('admin.dashboard', compact('orders', 'statuses', 'stats', 'filters'));
    }

    public function show(string $orderNumber): View
    {
        $order = Order::query()
            ->with('items')
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        return view('admin.orders.show', compact('order'));
    }
}
