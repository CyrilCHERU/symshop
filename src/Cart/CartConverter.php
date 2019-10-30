<?php

namespace App\Cart;

use App\Cart\CartManager;
use App\Entity\OrderInfo;
use App\Entity\OrderItem;
use Doctrine\ORM\EntityManagerInterface;

class CartConverter
{
    protected $cartManager;

    public function __construct(CartManager $cartManager, EntityManagerInterface $em)
    {
        $this->cartManager = $cartManager;
        $this->em = $em;
    }
    public function convertAndSaveToOrder(OrderInfo $order)
    {
        // Creer les entites qui correspondent au panier de la session
        $cart = $this->cartManager->all();



        foreach ($cart as $cartItem) {

            $orderItem = new OrderItem;

            $product = $this->em->merge($cartItem->getProduct());

            $orderItem->setPrice($cartItem->getProduct()->getPrice())
                ->setQuantity($cartItem->getQuantity())
                ->setProduct($product);

            $order->addItem($orderItem);

            $this->em->persist($orderItem);
        }
        $this->em->persist($order);
        $this->em->flush();
    }
}
