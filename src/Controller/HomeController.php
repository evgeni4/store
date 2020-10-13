<?php

namespace App\Controller;

use App\Repository\CategoriesRepository;
use App\Service\Categories\CategoriesService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @var CategoriesService
     */
private $categoryService;
public function __construct(CategoriesService $categoryService)
{
    $this->categoryService=$categoryService;
}

    /**
     * @Route("/home", name="home")
     */
    public function index()
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    /**
     * @Route("/category/{id}", name="category_view")
     */
    public function cat()
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
