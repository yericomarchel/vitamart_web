<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use App\Models\CartItem;
use App\Http\Requests\v1\StoreCartItemRequest;
use App\Http\Requests\v1\UpdateCartItemRequest;

use function Laravel\Prompts\error;

class CartItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(StoreCartItemRequest $request)
    {
        try {
            DB::beginTransaction();

            // Get the existing items in the cart
            $existingItems = CartItem::whereIn('product_id', collect($request->items)->pluck('product_id'))
                ->where('cart_id', $request->items[0]['cart_id']) // Assuming cart_id is the same for all items
                ->get();

            // Create, update, or remove items based on the request data
            foreach ($request->items as $itemData) {
                $existingItem = $existingItems->where('product_id', $itemData['product_id'])->first();

                if ($existingItem) {
                    // Update the existing item
                    $existingItem->update([
                        'quantity' => $itemData['quantity'],
                        'subtotal' => $itemData['subtotal'],
                        // Add other fields as needed
                    ]);

                    // Remove the existing item from the collection to track which items need to be removed later
                    $existingItems = $existingItems->reject(function ($item) use ($existingItem) {
                        return $item->id === $existingItem->id;
                    });
                } else {
                    // Create a new item
                    CartItem::create([
                        'cart_id' => $itemData['cart_id'],
                        'product_id' => $itemData['product_id'],
                        'quantity' => $itemData['quantity'],
                        'subtotal' => $itemData['subtotal'],
                    ]);
                }
            }

            foreach ($existingItems as $item) {
                $item->delete();
            }

            DB::commit();

            return response()->json(['message' => 'Items successfully stored']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to store item(s)']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(CartItem $cartItem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CartItem $cartItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCartItemRequest $request, CartItem $cartItem)
    {
        try {
            DB::beginTransaction();

            // Get the existing items in the cart
            $existingItems = CartItem::whereIn('product_id', collect($request->items)->pluck('product_id'))
                ->where('cart_id', $request->items[0]['cart_id']) // Assuming cart_id is the same for all items
                ->get();

            // Create, update, or remove items based on the request data
            foreach ($request->items as $itemData) {
                $existingItem = $existingItems->where('product_id', $itemData['product_id'])->first();

                if ($existingItem) {
                    // Update the existing item
                    $existingItem->update([
                        'quantity' => $itemData['quantity'],
                        'subtotal' => $itemData['subtotal'],
                        // Add other fields as needed
                    ]);

                    // Remove the existing item from the collection to track which items need to be removed later
                    $existingItems = $existingItems->reject(function ($item) use ($existingItem) {
                        return $item->id === $existingItem->id;
                    });
                } else {
                    // Create a new item
                    CartItem::create([
                        'cart_id' => $itemData['cart_id'],
                        'product_id' => $itemData['product_id'],
                        'quantity' => $itemData['quantity'],
                        'subtotal' => $itemData['subtotal'],
                    ]);
                }
            }

            foreach ($existingItems as $item) {
                $item->delete();
            }

            DB::commit();

            return response()->json(['message' => 'Items successfully stored']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to store item(s)']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CartItem $cartItem)
    {
        //
    }
}
