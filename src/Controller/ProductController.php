<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/produits", name="product_index")
     */
    public function index(ProductRepository $productRepository)
    {
        $products = $productRepository->findAll();

        return $this->render('product/index.html.twig', [
            'products' => $products
        ]);
    }

    /**
     * @Route("/produits/new", name="product_new")
     *
     * @return void
     */
    public function new(Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(ProductType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $product = $form->getData();

            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('product_show', [
                'id' => $product->getId()
            ]);
        }

        return $this->render('/product/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/produits/{id}/edit", name="product_edit")
     *
     * @return void
     */
    public function edit(Request $request, EntityManagerInterface $em, Product $product)
    {
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->flush();

            return $this->redirectToRoute('product_show', [
                'id' => $product->getId()
            ]);
        }

        return $this->render('/product/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/produits/{id}", name="product_show")
     *
     * @return void
     */
    public function show(Product $product)
    {
        return $this->render('/product/show.html.twig', [
            'product' => $product
        ]);
    }
}
