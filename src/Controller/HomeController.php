<?php

namespace App\Controller;

use App\Repository\CategoriesRepository;
use App\Repository\SettingsRepository;
use App\Service\Categories\CategoriesService;
use App\Service\Settings\SettingsServiceInterface\SettingsServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @var CategoriesService
     */
    private $categoryService;
    private $settingsService;

    public function __construct(SettingsServiceInterface $settingsService, CategoriesService $categoryService)
    {
        $this->settingsService = $settingsService;
        $this->categoryService = $categoryService;
    }

    /**
     * @Route("/home", name="home")
     */
    public function index()
    {
        if ($this->settingsService->settingsFind()->getStatus() == null) {
            $this->redirectToRoute('home');
            return $this->render('home/not-works.html.twig');
        }
        return $this->render('home/index.html.twig');
    }
    /**
     * @Route("/status", name="status_upd")
     */
    public function status()
    {
        $setting = $this->settingsService->settingsFind();
        $this->settingsService->statusUpdate($setting);
        return $this->redirectToRoute('home');
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
