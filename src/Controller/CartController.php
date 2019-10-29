<?php

namespace App\Controller;

use App\Cart\CartManager;
use App\Entity\Product;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="cart_index")
     */
    public function index(CartManager $cartManager)
    {
        return $this->render("cart/index.html.twig", [
            'items' => $cartManager->all(),
            'total' => $cartManager->getTotal()
        ]);
    }

    /**
     * @Route("/cart/add/{slug}", name="cart_add")
     *
     * @return void
     */
    public function add(Product $product, CartManager $cartManager, Request $request)
    {
        //     if(!$session->has('cart')){
        //         $cart = [];
        // } else {
        //     $cart = $session->get('cart');
        // } ou
        $cartManager->add($product);

        if ($referer = $request->server->get('HTTP_REFERER', null)) {
            return $this->redirect($referer);
        }

        return $this->redirectToRoute('product_show', ['slug' => $product->getSlug()]);
    }

    /**
     * @Route("/cart/empty", name="cart_empty")
     *
     * @param CartManager $cartManager
     * @return void
     */
    public function empty(CartManager $cartManager, Request $request)
    {
        $cartManager->empty($cart);

        if ($referer = $request->server->get('HTTP_REFERER', null)) {
            return $this->redirect($referer);
        }

        return $this->redirectToRoute('cart_index');
    }

    /**
     * @Route("/cart/remove/{slug}", name="cart_remove")
     *
     * @param Product $product
     * @param CartManager $cartManager
     * @param Request $request
     * @return void
     */
    public function remove(Product $product, CartManager $cartManager, Request $request)
    {
        $cartManager->remove($product);

        if ($referer = $request->server->get('HTTP_REFERER', null)) {
            return $this->redirect($referer);
        }

        return $this->redirectToRoute('cart_index');
    }
}
