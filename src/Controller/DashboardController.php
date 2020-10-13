<?php

namespace App\Controller;

use App\Repository\CategoriesRepository;
use App\Service\Users\UserService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    private $userService;
    private $categoryRepository;

    /**
     * DashboardController constructor.
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {

        $this->userService = $userService;
    }

    /**
     * @Route("/dashboard", name="user_office")
     * @Security ("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function index()
    {
        return $this->render('dashboard/dashboard.html.twig', [
            'controller_name' => 'DashboardController',
            'user'=>$this->userService->currentUser()
        ]);
    }

}
