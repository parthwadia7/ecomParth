<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\OrderPlaced;

class ShoppingCartController extends Controller
{
    
    public function index()
    {
        $cartItems = session()->get('cart', []);
        $totals = $this->calculateCartTotals();

        return view('cart.index', compact('cartItems', 'totals'));
    }

    public function add(Request $request)
    {
        $product = Product::find($request->product_id);
    
        if (!$product || $product->stock < $request->quantity) {
            return redirect()->back()->withErrors('Insufficient stock');
        }
    
        $cart = session()->get('cart', []);
    
        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $request->quantity;
        } else {
            $cart[$product->id] = [
                "name" => $product->name,
                "price" => $product->price,
                "quantity" => $request->quantity,
            ];
        }
    
        session()->put('cart', $cart);
    
        return redirect()->route('cart.index')->with('success', 'Product added to cart!');
    }
    public function remove(Request $request)
    {
        $cart = session()->get('cart');
    
        if (isset($cart[$request->product_id])) {
            unset($cart[$request->product_id]);
            session()->put('cart', $cart);
        }
    
        return redirect()->route('cart.index')->with('success', 'Product removed from cart');
    }
    public function checkout()
    {
        $cart = session()->get('cart', []);
    
        if (empty($cart)) {
            return redirect()->route('cart.index')->withErrors('Your cart is empty');
        }
    
        DB::transaction(function () use ($cart) {
            $order = Order::create([
                'user_id' => auth()->id(),
                'total' => collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']),
            ]);
    
            foreach ($cart as $productId => $item) {
                $product = Product::find($productId);
                
                if ($product->stock < $item['quantity']) {
                    throw new \Exception('Insufficient stock for ' . $product->name);
                }
    
                $product->decrement('stock', $item['quantity']);
                $order->products()->attach($productId, [
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            }
    
            session()->forget('cart');
        });
    
        return redirect()->route('cart.index')->with('success', 'Order placed successfully');
    }
    private function calculateCartTotals()
    {
        $cart = session()->get('cart', []);
        $subtotal = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);
        $tax = $subtotal * 0.1; // Example 10% tax rate
        $total = $subtotal + $tax;
    
        return compact('subtotal', 'tax', 'total');
    }
    public function applyDiscount($code)
    {
        $discount = Discount::where('code', $code)->first();
    
        if ($discount && $discount->isValid()) {
            session()->put('discount', $discount->value);
        }
    
        return redirect()->route('cart.index');
    }
    
    private function validateOrder()
    {
        $cart = session()->get('cart', []);
        $errors = [];

        foreach ($cart as $productId => $item) {
            $product = Product::find($productId);

            if (!$product || $product->stock < $item['quantity']) {
                $errors[] = "Insufficient stock for product: {$item['name']}";
            }
        }

        return $errors;
    }
    private function validateDiscount($code)
    {
        $discount = Discount::where('code', $code)->first();
    
        if (!$discount || !$discount->isValid()) {
            return 'Invalid or expired discount code.';
        }
    
        session()->put('discount', $discount);
        return null;
    }
    private function completeOrder()
    {
       DB::transaction(function () {
           $cart = session()->get('cart', []);
           $discount = session()->get('discount');
           
           $order = Order::create([
               'user_id' => auth()->id(),
               'status' => 'Pending',
               'total' => $this->calculateTotal($cart, $discount),
           ]);
         
           foreach ($cart as $productId => $item) {
               $product = Product::find($productId);
               $product->decrement('stock', $item['quantity']);
               $order->products()->attach($productId, [
                   'quantity' => $item['quantity'],
                   'price' => $item['price'],
               ]);
           }
    
           session()->forget(['cart', 'discount']);
       });
    
       return redirect()->route('cart.index')->with('success', 'Order completed successfully');
    }
    private function calculateShippingFee($destination, $weight)
    {
        
        $shippingFee = 10; // For example purposes
        return $shippingFee;
    }
    
        
}
