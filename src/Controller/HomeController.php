<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(ProductRepository $productRepository, CategoryRepository $categoryRepository)
    {
        $featured = $productRepository->findBy(['featured' => true]);
        $categories = $categoryRepository->findAll();

        return $this->render('home/index.html.twig', [
            'featured' => $featured,
            'categories' => $categories
        ]);
    }
}
