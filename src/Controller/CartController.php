<?php

namespace App\Controller;

use App\Cart\CartConverter;
use App\Cart\CartManager;
use App\Entity\OrderInfo;
use App\Entity\Product;
use App\Form\ShippingType;
use App\Payment\StripeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="cart_index")
     */
    public function index(
        CartManager $cartManager,
        Request $request,
        CartConverter $cartConverter,
        SessionInterface $session
    ) {
        $form = $this->createForm(ShippingType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $order = $form->getData();
            // ajout du user a l order
            $order->setUser($this->getUser());
            // converti les produits du panier en entité orderItem et sauvegarde le tout
            $cartConverter->convertAndSaveToOrder($order);

            // Ajout de la commande en session pour l'avoir sous la main
            $session->set('currentOrder', $order);

            return $this->redirectToRoute('cart_payment', ['id' => $order->getId()]);
        }

        return $this->render("cart/index.html.twig", [
            'items' => $cartManager->all(),
            'total' => $cartManager->getTotal(),
            'form' => $form->createView()
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
        $cartManager->empty();

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

    /**
     * @Route("/cart/payment", name="cart_payment")
     *
     * @param SessionInterface $session
     * @return void
     */
    public function payment(SessionInterface $session)
    {

        $order = $session->get('currentOrder');

        return $this->render('cart/payment.html.twig', [
            'order' => $order
        ]);
    }

    /**
     * @Route("/cart/success", name="cart_success")
     *
     * @return void
     */
    public function success(SessionInterface $session, EntityManagerInterface $em)
    {
        // On change le status de la commande
        $order = $em->merge($session->get('currentOrder'));
        $order->setStatus(OrderInfo::STATUS_PAYMENT_VALIDATED);

        $em->flush();

        // on vide le panier et la session

        $session->remove('currentOrder');
        $session->remove('cart');

        // On affiche Bravo

        return $this->render('cart/success.html.twig');
    }

    /**
     * @Route("/cart/process", name="cart_process")
     *
     * @return void
     */
    public function process(Request $request, StripeService $stripe)
    {
        $session = $request->getSession();
        $order = $session->get('currentOrder');
        $token = $request->request->get('stripeToken');

        $paid = $stripe->processCharge($order->getTotal(), $token, "Paiement d'exemple");

        if (!$paid) {
            return $this->redirectToRoute("cart_payment");
        }

        return $this->redirectToRoute("cart_success");
    }
}
