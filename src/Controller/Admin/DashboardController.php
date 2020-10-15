<?php

namespace App\Controller\Admin;

use App\Entity\Categories;
use App\Entity\Settings;
use App\Entity\User;
use App\Repository\UserRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class DashboardController extends AbstractDashboardController
{

    /**
     * @Route("/office", name="office")
     */
    public function index(): Response
    {
        return parent::index();
    }

    public function configureUserMenu(UserInterface $user): UserMenu
    {
        return parent::configureUserMenu($user)
            ->addMenuItems([
                //  MenuItem::linktoDashboard('__ea__page_title.dashboard', 'fa fa-home'),
                //MenuItem::linkToRoute('My Profile', 'fa fa-id-card', 'user', ['...' => '...'])
                // MenuItem::linkToRoute('My Profile', 'fa fa-user', 'user_profile', []),
                // MenuItem::linkToLogout('Settings', 'fa fa-sign-out')
            ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Admin panel</br>' . $this->getUser());
    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::linkToDashboard('Dashboard', 'fa fa-home ')->setCssClass('sidebar-link'),
            MenuItem::subMenu('User', 'fa fa-users')
                ->setCssClass('sidebar-link collapsed')
                ->setSubItems(
                    [
                        MenuItem::linkToCrud('Customers', 'fa fa-users', User::class)
                            ->setCssClass('sidebar-link',)
                    ]
                ),
            MenuItem::linkToCrud('Settings', 'fa fa-cogs', Settings::class)
                ->setCssClass('sidebar-link'),
            MenuItem::linkToLogout('Logout', 'fa fa-sign-out',)->setCssClass('sidebar-link collapsed')

        ];
    }
}
