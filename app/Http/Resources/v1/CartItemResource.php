<?php

namespace App\Http\Resources\v1;

use App\Http\Resources\v1\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'cartId' => $this->cart_id,
            'product' => new ProductResource($this->product),
            'quantity' => $this->quantity,
            'subtotal' => $this->subtotal,
        ];
    }
}
