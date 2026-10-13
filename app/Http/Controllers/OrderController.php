<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Display the authenticated user's order history.
     */
    public function index(Request $request): View
    {
        $orders = $request->user()->orders()
            ->with(['items.product.category'])
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    /**
     * Display specific order details.
     */
    public function show(Request $request, Order $order): View
    {
        // Customer can only view their own order; admin can view all
        if (! $request->user()->isAdmin() && $order->user_id !== $request->user()->id) {
            abort(403, 'Anda tidak berhak melihat pesanan pengguna lain.');
        }

        $order->load(['user', 'items.product.category']);

        return view('orders.show', compact('order'));
    }

    /**
     * Quick checkout demonstration to place an order.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:10'],
            'shipping_address' => ['required', 'string', 'max:500'],
            'payment_method' => ['required', 'string'],
            'notes' => ['nullable', 'string', 'max:255'],
        ]);

        $product = Product::findOrFail($validated['product_id']);

        if ($product->stock < $validated['quantity']) {
            return back()->withErrors(['quantity' => 'Stok produk tidak mencukupi untuk jumlah yang dipesan.']);
        }

        $total = $product->price * $validated['quantity'];

        $order = Order::create([
            'user_id' => $request->user()->id,
            'order_number' => 'ORD-' . strtoupper(Str::random(8)) . '-' . rand(100, 999),
            'total_amount' => $total,
            'status' => Order::STATUS_PROCESSING,
            'shipping_address' => $validated['shipping_address'],
            'payment_method' => $validated['payment_method'],
            'payment_status' => 'paid',
            'notes' => $validated['notes'],
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => $validated['quantity'],
            'unit_price' => $product->price,
            'subtotal' => $total,
        ]);

        // Reduce product stock
        $product->decrement('stock', $validated['quantity']);

        return redirect()->route('orders.show', $order)
            ->with('status', 'Pesanan berhasil dibuat! Nomor pesanan Anda: ' . $order->order_number);
    }
}
