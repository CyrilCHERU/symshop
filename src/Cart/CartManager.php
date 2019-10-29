<?php

namespace App\Cart;

use App\Entity\Product;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartManager
{

    protected $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * Ajoute un produit dans le panier
     *
     * @param Product $product
     * @return Array
     */
    public function add(Product $product)
    {
        $cart = $this->all();

        if ($this->has($product)) {
            $cart[$product->getId()]->increment();
        } else {
            $cart[$product->getId()] = new CartItem($product);
        }

        $this->save($cart);
    }

    /**
     * retourne la totalite du panier
     *
     * @return void
     */
    public function all()
    {
        return $this->session->get('cart', []);
    }

    /**
     * Retourne le total par produit
     *
     * @return integer
     */
    public function getTotal(): int
    {
        $cart = $this->all();

        $total = 0;

        foreach ($cart as $item) {
            $total += $item->getTotal();
        }

        return $total;

        // OU encore ...
        // return array_reduce($this->all(), function(int $total, CartItem $item{
        //     return $total + $item->getTotal()
        // }, 0));
    }

    /**
     * Vider le panier
     *
     * @return void
     */
    public function empty($cart)
    {
        $this->save($cart);
    }

    /**
     * Retirer un article
     *
     * @return void
     */
    public function remove(Product $product)
    {
        $cart = $this->all();

        if (!$this->has($product)) {
            return;
        }

        $cartItem = $this->getItem($product);

        if ($cartItem->getQuantity() > 1) {
            $cartItem->decrement();
        } else {
            unset($cart[$product->getId()]);
        }

        $this->session->set('cart', $cart);
    }

    /**
     * Sauve le panier
     *
     * @param array $cart
     * @return void
     */
    protected function save(array $cart)
    {
        $this->session->set('cart', $cart);
    }

    /**
     * Retourne true si un produit existe dans le panier
     *
     * @param Product $product
     * @return boolean
     */
    protected function has(Product $product)
    {
        $cart = $this->all();

        return !empty($cart[$product->getId()]);
    }

    /**
     * Retourne le cartItem correspondant à un produit donné
     *
     * @param Product $product
     * @return CartItem
     */
    protected function getItem(Product $product): CartItem
    {
        $cart = $this->all();
        return $cart[$product->getId()];
    }
}
