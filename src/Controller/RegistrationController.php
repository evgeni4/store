<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\CountriesRepository;
use App\Security\EmailVerifier;
use App\Service\Users\UserService;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    private $emailVerifier;
    private $userService;
    private $countriesRepository;

    public function __construct(CountriesRepository $countriesRepository, UserService $userService, EmailVerifier $emailVerifier)
    {
        $this->countriesRepository = $countriesRepository;
        $this->userService = $userService;
        $this->emailVerifier = $emailVerifier;
    }

    /**
     * @Route("/register", name="user_register")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('user_office');
        }
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);

        $form->remove('avatar');
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setAvatar(0);
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $this->userService->save($user);
            $this->addFlash('success', 'An email has been sent to your email to confirm registration.');
            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('info@store.my', 'Confirm email address!'))
                    ->to($user->getEmail())
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );
            // do anything else you need here, like send an email
            $this->addFlash('success', 'An email has been sent to your email to confirm registration.');
            return $this->redirectToRoute('app_login');
        }
        return $this->render('registration/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/verify/email", name="app_verify_email")
     * @param Request $request
     * @return Response
     */
    public function verifyUserEmail(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());
            return $this->redirectToRoute('user_register');
        }
        if ($this->getUser()) {
            $this->addFlash('success', 'Your email address has been verified.');
            return $this->redirectToRoute('user_office');
        }
        return $this->redirectToRoute('app_login');
    }
}
