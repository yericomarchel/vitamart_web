<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\StoreCartItemRequest;
use App\Http\Resources\v1\CartResource;
use App\Models\Cart;
use App\Http\Requests\v1\StoreCartRequest;
use App\Http\Requests\v1\UpdateCartRequest;
use App\Models\CartItem;
use App\Models\Product;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($userId)
    {
        $cart = Cart::where('user_id', $userId)
            ->where('is_active', true)
            ->with('items')
            ->first();

        if (!$cart) {
            // Handle the case when the cart is not found
            return response()->json(['error' => 'No active cart'], 404);
        }

        return new CartResource($cart);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCartRequest $request)
    {
        $cart = Cart::create([
            'user_id' => $request->user_id,
            'is_aktif' => True,
            'total' => 0,
        ]);

        return response()->json(['message' => 'Cart created', 'cart' => $cart]);
    }

    /**
     * Display the specified resource.
     */
    public function show($userId, $cartId)
    {
        $cart = Cart::where('user_id', $userId)
            ->where('id', $cartId)
            ->with('items')
            ->first();

        if (!$cart) {
            // Handle the case when the cart is not found
            return response()->json(['error' => 'Cart not found'], 404);
        }

        // Use CartResource to transform the cart data
        return new CartResource($cart);
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCartRequest $request, Cart $cart)
    {

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cart $cart)
    {
        //
    }

    public function expireCart($cartId)
    {
        $cart = Cart::where('id', $cartId)->first();

        if (!$cart) {

            return response()->json(['error' => 'Cart not found'], 404);
        }

        $cart->update(['is_active' => false]);

        return response()->json(['message' => 'Cart expired']);
    }
}
