<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePasswordFormType;
use App\Form\RegistrationFormType;
use App\Form\ResetPasswordRequestFormType;
use App\Form\UserEditFormType;
use App\Repository\CountriesRepository;
use App\Repository\StatesRepository;
use App\Service\Users\UserService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\File;

class UserController extends AbstractController
{
    /**
     * @var CountriesRepository $countyRepository
     */
    private $countyRepository;
    /**
     * @var StatesRepository $stateRepository
     */
    private $stateRepository;
    /**
     * @var UserService $userService
     */
    private $userService;

    public function __construct(CountriesRepository $countriesRepository, StatesRepository $stateRepository, UserService $userService)
    {
        $this->countyRepository = $countriesRepository;
        $this->stateRepository = $stateRepository;
        $this->userService = $userService;
    }

    /**
     * @Route("/dashboard/user", name="user_profile")
     * @Security ("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function index()
    {
        $currentUser = $this->userService->currentUser();
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
            'user' => $currentUser
        ]);
    }

    /**
     * @Route("/dashboard/user-edit", name="profile_edit", methods={"GET"})
     * @Security ("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function editProfile()
    {
        $currentUser = $this->userService->currentUser();
        $form=$this->createForm(RegistrationFormType::class,$currentUser);
        $form->remove('email');

        $form->remove('plainPassword');
        $form->remove('agreeTerms');
        return $this->render('user/profile.edit.html.twig', [
            'controller_name' => 'UserController',
            'form' => $form->createView(),
            'user' => $currentUser,
        ]);
    }

    /**
     * @Route ("/dashboard/user-edit",  methods={"POST"})
     * @Security ("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Request $request
     * @return Response
     */
    public function editProfileProcess(Request $request)
    {
        $currentUser = $this->userService->currentUser();
        $form = $this->createForm(RegistrationFormType::class, $currentUser);

        $form->remove('email');
        $form->remove('plainPassword');

        $form->remove('agreeTerms');

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
           // var_dump($request->request->all());exit;
            $this->uploadFile($form, $currentUser);
            $this->userService->edit($currentUser);
            $this->addFlash('success', 'Update Profile successfully!');
            return $this->redirectToRoute('user_profile');
        }
        return $this->render('user/profile.edit.html.twig', [
            'controller_name' => 'UserController',
            'form' => $form->createView(),
            'user' => $currentUser
        ]);
    }

    /**
     * @Route ("/dashboard/user/password",name="change_password",methods={"GET"})
     * @Security ("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function changePassword()
    {
        $currentUser = $this->userService->currentUser();
        $form = $this->createForm(ChangePasswordFormType::class, $currentUser);
        return $this->render('user/change_password.html.twig', [
            'controller_name' => 'UserController',
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route ("/dashboard/user/password",methods={"POST"})
     * @Security ("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Request $request
     * @return Response
     */
    public function changePasswordProcess(Request $request)
    {
        $currentUser = $this->userService->currentUser();
        $form = $this->createForm(ChangePasswordFormType::class, $currentUser);
        $form->handleRequest($request);
        $data = $request->request->get('change_password_form');
        if ($form->isSubmitted() && $form->isValid()) {
            $pass = $data['plainPassword']['first'];
            $this->userService->updatePassword($currentUser, $pass);
            $this->addFlash('success', 'Update password successfully!');
            return $this->redirectToRoute('user_profile');
        }
        return $this->render('user/change_password.html.twig', [
            'controller_name' => 'UserController',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param FormInterface $form
     * @param User|null $currentUser
     */
    private function uploadFile(FormInterface $form, ?User $currentUser): void
    {
        /**
         * @var UploadedFile $file
         */
        $file = $form['avatar']->getData();
        if ($file !== null) {
            $fs = new Filesystem();
            $path = $this->getParameter('user_image') . $currentUser->getAvatar();

            if ($path) {
            $fs->remove($path);
            }
            $fileName = md5(uniqid()) . "." . $file->guessExtension();
            $file->move(
                $this->getParameter('user_image'),
                $fileName
            );
            $currentUser->setAvatar($fileName);
        }
    }
}
