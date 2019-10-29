<?php

namespace App\Cart;

use App\Entity\Product;

class CartItem
{

    protected $product;
    protected $quantity;

    public function __construct(Product $product, int $quantity = 1)
    {
        $this->product = $product;
        $this->quantity = $quantity;
    }

    /**
     * Incremente un produit dans un panier
     *
     * @param integer $add
     * @return self
     */
    public function increment(int $add = 1): self
    {
        $this->quantity += $add;
        return $this;
    }

    /**
     * Decremente un produit dans un panier
     *
     * @param integer $sub
     * @return void
     */
    public function decrement(int $sub = 1)
    {
        $this->quantity -= $sub;
        return $this;
    }

    public function setProduct(Product $product)
    {
        $this->product = $product;
        return $this;
    }

    public function getProduct()
    {
        return $this->product;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function getTotal(): int
    {
        return $this->product->getPrice() * $this->quantity;
    }
}
