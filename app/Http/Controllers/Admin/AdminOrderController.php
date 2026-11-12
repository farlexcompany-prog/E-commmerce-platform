<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;
use RuntimeException;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminOrderController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $this->validatedFilters($request);
        $orders = $this->filteredOrders($filters)
            ->paginate((int) ($filters['per_page'] ?? 10))
            ->withQueryString();
        $statuses = Order::query()->select('status')->distinct()->orderBy('status')->pluck('status');

        return view('admin.dashboard', compact('orders', 'statuses', 'filters'));
    }

    public function export(Request $request): StreamedResponse
    {
        $orders = $this->filteredOrders($this->validatedFilters($request))
            ->with('items')
            ->get();

        return response()->streamDownload(function () use ($orders) {
            $stream = fopen('php://output', 'w');
            if ($stream === false) {
                throw new RuntimeException('Unable to open the order export stream.');
            }

            fputcsv($stream, ['Order ID', 'Date', 'Status', 'Grand Total', 'Payment Method', 'Channel', 'Customer', 'Email', 'Phone', 'Address', 'Pincode', 'Items']);
            foreach ($orders as $order) {
                $values = [
                    $order->order_number,
                    $order->created_at->format('Y-m-d H:i:s'),
                    $order->status,
                    $order->total,
                    $order->payment_method,
                    'Farlex Store',
                    $order->customer_name,
                    $order->customer_email,
                    $order->customer_phone,
                    $order->shipping_address,
                    $order->pincode,
                    $order->items->map(fn ($item) => $item->product_name.' x '.$item->quantity)->implode('; '),
                ];

                fputcsv($stream, array_map(fn ($value) => $this->spreadsheetSafe((string) $value), $values));
            }

            fclose($stream);
        }, 'farlex-orders.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    private function validatedFilters(Request $request): array
    {
        return $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'string', 'max:60'],
            'from' => ['nullable', 'date_format:Y-m-d'],
            'to' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:from'],
            'per_page' => ['nullable', 'in:10,25,50'],
        ]);
    }

    private function filteredOrders(array $filters): Builder
    {
        $query = Order::query()
            ->with('items.product')
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

        return $query;
    }

    private function spreadsheetSafe(string $value): string
    {
        return preg_match('/^[\t\r ]*[=+\-@]/', $value) === 1 ? "'".$value : $value;
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
